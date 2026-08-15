<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class OrderService
{
    public function __construct(
        private readonly ActivityLogService $activityLogService,
    ) {}

    public static function generateOrderNumber(): string
    {
        $next = Order::withTrashed()->count() + 1;

        return sprintf('INV-%05d', $next);
    }

    /**
     * @return array{draw: int, recordsTotal: int, recordsFiltered: int, data: array<int, array<string, mixed>>}
     */
    public function datatable(Request $request): array
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 10);
        $search = trim((string) $request->input('search.value', ''));
        $status = $request->input('status');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $customer = trim((string) $request->input('customer', ''));

        $orderColumn = (int) $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';

        $query = Order::query()->with('user');

        $recordsTotal = Order::query()->count();

        if ($status) {
            $query->where('status', $status);
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        if ($customer !== '') {
            $query->where(function ($builder) use ($customer) {
                $builder->where('customer_name', 'like', "%{$customer}%")
                    ->orWhere('customer_email', 'like', "%{$customer}%")
                    ->orWhere('customer_phone', 'like', "%{$customer}%")
                    ->orWhere('order_number', 'like', "%{$customer}%")
                    ->orWhereHas('user', fn ($userQuery) => $userQuery
                        ->where('name', 'like', "%{$customer}%")
                        ->orWhere('email', 'like', "%{$customer}%"));
            });
        }

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = (clone $query)->count();

        $sortColumn = match ($orderColumn) {
            1 => 'order_number',
            3 => 'total_price',
            4 => 'status',
            5 => 'created_at',
            default => 'id',
        };

        $orders = $query
            ->orderBy($sortColumn, $orderDir)
            ->skip($start)
            ->take($length > 0 ? $length : 10)
            ->get();

        return [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $orders->map(fn (Order $order) => $this->formatDatatableRow($order))->all(),
        ];
    }

    public function updateOrder(Order $order, OrderStatus $status, PaymentStatus $paymentStatus): Order
    {
        return DB::transaction(function () use ($order, $status, $paymentStatus) {
            $previousStatus = $order->status;
            $previousPaymentStatus = $order->payment_status;

            $order->update([
                'status' => $status,
                'payment_status' => $paymentStatus,
            ]);

            if ($status === OrderStatus::Delivered && ! $order->stock_deducted) {
                $this->deductStock($order);
                $order->update(['stock_deducted' => true]);
            }

            $changes = [];

            if ($previousStatus !== $status) {
                $changes[] = "статус: {$previousStatus->label()} → {$status->label()}";
            }

            if ($previousPaymentStatus !== $paymentStatus) {
                $changes[] = "оплата: {$previousPaymentStatus->label()} → {$paymentStatus->label()}";
            }

            if ($changes !== []) {
                $this->activityLogService->log(
                    action: 'updated',
                    entityType: 'Order',
                    entityId: $order->id,
                    description: "Обновлён заказ {$order->order_number}: ".implode('; ', $changes),
                    properties: [
                        'order_number' => $order->order_number,
                        'previous_status' => $previousStatus->value,
                        'new_status' => $status->value,
                        'previous_payment_status' => $previousPaymentStatus->value,
                        'new_payment_status' => $paymentStatus->value,
                    ],
                );
            }

            return $order->fresh(['items.product', 'user', 'deliveryMethod']);
        });
    }

    private function deductStock(Order $order): void
    {
        $order->loadMissing('items');

        foreach ($order->items as $item) {
            if (! $item->product_id) {
                continue;
            }

            $product = Product::query()->lockForUpdate()->find($item->product_id);

            if (! $product) {
                continue;
            }

            if ($product->quantity < $item->quantity) {
                throw new RuntimeException(
                    "Недостаточно остатка для товара «{$item->product_name}» (доступно: {$product->quantity})."
                );
            }

            $product->decrement('quantity', $item->quantity);
        }
    }

    private function formatDatatableRow(Order $order): array
    {
        $status = $order->status;
        $total = number_format((float) $order->total_price + (float) $order->delivery_price, 2, '.', ' ');

        return [
            'id' => $order->id,
            'order_number' => '<a href="'.route('admin.orders.show', $order).'"><strong>'.e($order->order_number).'</strong></a>',
            'customer' => e($order->customerLabel()),
            'total' => $total.' ₽',
            'status' => '<span class="badge '.$status->badgeClass().'">'.e($status->label()).'</span>',
            'created_at' => $order->created_at?->format('d.m.Y H:i') ?? '—',
            'actions' => '<a href="'.route('admin.orders.show', $order).'" class="btn btn-sm btn-outline">Просмотр</a>',
        ];
    }
}

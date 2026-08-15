<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->has('draw')) {
            return response()->json($this->orderService->datatable($request));
        }

        return view('admin.orders.index');
    }

    public function show(Order $order): View
    {
        $order->load(['items.product', 'user', 'deliveryMethod']);

        return view('admin.orders.show', compact('order'));
    }

    public function update(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        try {
            $validated = $request->validated();

            $this->orderService->updateOrder(
                $order,
                OrderStatus::from($validated['status']),
                PaymentStatus::from($validated['payment_status']),
            );
        } catch (\RuntimeException $exception) {
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Заказ успешно обновлён.');
    }
}

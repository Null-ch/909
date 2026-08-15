<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Order::query()->with('user')->orderByDesc('created_at');
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return ['ID', '№ заказа', 'Клиент', 'Email', 'Телефон', 'Сумма', 'Доставка', 'Статус', 'Оплата', 'Дата'];
    }

    /**
     * @param  Order  $order
     * @return array<int, mixed>
     */
    public function map($order): array
    {
        return [
            $order->id,
            $order->order_number,
            $order->customer_name,
            $order->customer_email,
            $order->customer_phone,
            $order->total_price,
            $order->delivery_price,
            $order->status->label(),
            $order->payment_status->label(),
            $order->created_at?->format('d.m.Y H:i'),
        ];
    }
}

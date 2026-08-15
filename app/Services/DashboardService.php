<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DashboardService
{
    public function newOrdersTodayCount(): int
    {
        return Order::query()
            ->whereDate('created_at', Carbon::today())
            ->where('status', OrderStatus::New)
            ->count();
    }

    /**
     * @return Collection<int, Product>
     */
    public function lowStockProducts(int $threshold = 5): Collection
    {
        return Product::query()
            ->where('is_active', true)
            ->where('quantity', '<', $threshold)
            ->orderBy('quantity')
            ->limit(10)
            ->get(['id', 'name', 'sku', 'quantity']);
    }

    public function monthlyRevenue(): float
    {
        return (float) Order::query()
            ->where('payment_status', PaymentStatus::Paid)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_price');
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, float>}
     */
    public function weeklySalesChart(): array
    {
        $labels = [];
        $values = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d.m');

            $values[] = (float) Order::query()
                ->where('payment_status', PaymentStatus::Paid)
                ->whereDate('created_at', $date)
                ->sum('total_price');
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }
}

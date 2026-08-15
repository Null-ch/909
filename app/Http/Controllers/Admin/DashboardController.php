<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService,
    ) {}

    public function index(): View
    {
        $chart = $this->dashboardService->weeklySalesChart();

        return view('admin.dashboard', [
            'newOrdersToday' => $this->dashboardService->newOrdersTodayCount(),
            'lowStockProducts' => $this->dashboardService->lowStockProducts(),
            'monthlyRevenue' => $this->dashboardService->monthlyRevenue(),
            'chartLabels' => $chart['labels'],
            'chartValues' => $chart['values'],
        ]);
    }
}

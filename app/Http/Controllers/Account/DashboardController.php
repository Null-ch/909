<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $recentOrders = $user->orders()
            ->with('deliveryMethod')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('account.dashboard', [
            'user' => $user,
            'recentOrders' => $recentOrders,
            'ordersCount' => $user->orders()->count(),
            'metaTitle' => 'Личный кабинет — '.setting('shop_name'),
        ]);
    }
}

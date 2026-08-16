<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Auth::user()->orders()
            ->with('deliveryMethod')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('account.orders.index', [
            'orders' => $orders,
            'metaTitle' => 'Мои заказы — '.setting('shop_name'),
        ]);
    }

    public function show(Order $order): View
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $order->load(['items', 'deliveryMethod']);

        return view('account.orders.show', [
            'order' => $order,
            'metaTitle' => "Заказ {$order->order_number} — ".setting('shop_name'),
        ]);
    }
}

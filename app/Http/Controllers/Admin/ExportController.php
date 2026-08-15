<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrdersExport;
use App\Exports\ProductsExport;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function products(): BinaryFileResponse
    {
        logActivity('updated', 'Export', null, 'Экспорт списка товаров в Excel');

        return (new ProductsExport)->download('products-'.now()->format('Y-m-d').'.xlsx');
    }

    public function orders(): BinaryFileResponse
    {
        logActivity('updated', 'Export', null, 'Экспорт списка заказов в Excel');

        return (new OrdersExport)->download('orders-'.now()->format('Y-m-d').'.xlsx');
    }

    public function users(): BinaryFileResponse
    {
        logActivity('updated', 'Export', null, 'Экспорт списка пользователей в Excel');

        return (new UsersExport)->download('users-'.now()->format('Y-m-d').'.xlsx');
    }
}

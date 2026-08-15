@extends('layouts.gentelella')

@section('title', 'Заказы')
@section('page', 'orders')
@section('breadcrumb', 'Главная > Заказы')

@push('scripts')
    @vite(['resources/js/admin-orders-index.js'])
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Продажи</div>
                <h1 class="page-title">Заказы</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.export.orders') }}" class="btn btn-outline">Экспорт в Excel</a>
            </div>
        </div>
    </div>

    <div class="admin-card-stack">
        <div class="card">
            <div class="card-body">
                <div class="form-row" id="order-filters">
                    <div class="form-group">
                        <label class="form-label" for="filter-status">Статус</label>
                        <select id="filter-status" class="form-control">
                            <option value="">Все статусы</option>
                            @foreach (\App\Enums\OrderStatus::cases() as $status)
                                <option value="{{ $status->value }}">{{ $status->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="filter-date-from">Дата от</label>
                        <input type="date" id="filter-date-from" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="filter-date-to">Дата до</label>
                        <input type="date" id="filter-date-to" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="filter-customer">Покупатель</label>
                        <input type="text" id="filter-customer" class="form-control" placeholder="Имя, email, телефон, № заказа">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Список заказов</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-responsive">
                    <table
                        id="orders-table"
                        class="table"
                        data-datatable-server
                        data-ajax-url="{{ route('admin.orders.index') }}"
                        style="width:100%"
                    >
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>№ заказа</th>
                                <th>Клиент</th>
                                <th>Сумма</th>
                                <th>Статус</th>
                                <th>Дата</th>
                                <th data-orderable="false" style="text-align:right">Действия</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

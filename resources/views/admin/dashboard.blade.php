@extends('layouts.gentelella')

@section('title', 'Админ-панель')
@section('page', 'dashboard')
@section('breadcrumb', 'Главная > Панель управления')

@push('scripts')
    @vite(['resources/js/admin-dashboard.js'])
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Обзор</div>
                <h1 class="page-title">Панель управления</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">Все заказы</a>
            </div>
        </div>
    </div>

    <div class="row col-3">
        <div class="card">
            <div class="stat">
                <div class="stat-icon blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M6 2h9l3 3v13a1 1 0 01-1 1H6a1 1 0 01-1-1V3a1 1 0 011-1z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Новые заказы сегодня</div>
                    <div class="stat-value-row">
                        <span class="stat-value">{{ $newOrdersToday }}</span>
                    </div>
                    <div class="stat-subtext">Со статусом «Новый»</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="stat">
                <div class="stat-icon yellow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 9v4M12 17h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Низкий остаток</div>
                    <div class="stat-value-row">
                        <span class="stat-value">{{ $lowStockProducts->count() }}</span>
                    </div>
                    <div class="stat-subtext">Меньше 5 шт.</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="stat">
                <div class="stat-icon teal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Выручка за месяц</div>
                    <div class="stat-value-row">
                        <span class="stat-value">{{ number_format($monthlyRevenue, 0, '.', ' ') }} ₽</span>
                    </div>
                    <div class="stat-subtext">{{ now()->translatedFormat('F Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row col-2" style="margin-top: var(--admin-gap, 24px);">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Продажи за неделю</div>
                <div class="card-subtitle">Оплаченные заказы</div>
            </div>
            <div class="card-body">
                <canvas
                    id="sales-chart"
                    height="220"
                    data-labels='@json($chartLabels)'
                    data-values='@json($chartValues)'
                ></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Товары с низким остатком</div>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Товар</th>
                                <th>SKU</th>
                                <th>Остаток</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lowStockProducts as $product)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.products.edit', $product) }}">{{ $product->name }}</a>
                                    </td>
                                    <td>{{ $product->sku }}</td>
                                    <td><span class="badge badge-red">{{ $product->quantity }} шт.</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="text-align:center;color:var(--text-muted);padding:24px">
                                        Все товары в норме
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

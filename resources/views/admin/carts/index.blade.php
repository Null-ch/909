@extends('layouts.gentelella')

@section('title', 'Корзины')
@section('page', 'carts')
@section('breadcrumb', 'Главная > Корзины')

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Аналитика</div>
                <h1 class="page-title">Активные корзины</h1>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Корзины за последние 6 часов</div>
                <div class="card-subtitle">Только просмотр — помогает отслеживать брошенные корзины.</div>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Покупатель</th>
                            <th>Товары</th>
                            <th>Сумма</th>
                            <th>Обновлено</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($carts as $cart)
                            <tr>
                                <td>{{ $cart['buyer'] }}</td>
                                <td>{{ $cart['products'] }}</td>
                                <td>{{ $cart['total'] }}</td>
                                <td>{{ $cart['updated_at'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;color:var(--text-muted);padding:24px">
                                    Активных корзин не найдено
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

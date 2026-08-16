@extends('layouts.app')

@section('meta_title', $metaTitle)

@section('breadcrumbs')
    @include('front.partials.breadcrumbs', ['items' => [
        ['title' => 'Личный кабинет', 'url' => route('account.dashboard')],
        ['title' => 'Мои заказы', 'url' => route('account.orders.index')],
        ['title' => $order->order_number, 'url' => route('account.orders.show', $order)],
    ]])
@endsection

@section('content')
    <div class="account-page py-4">
        <div class="container">
            <h1 class="h2 mb-4">Личный кабинет</h1>

            <div class="row g-4">
                <div class="col-lg-3">
                    @include('account.partials.nav')
                </div>

                <div class="col-lg-9">
                    <div class="card border-0 shadow-sm p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h2 class="h5 mb-0">Заказ {{ $order->order_number }}</h2>
                            <span class="badge {{ $order->status->bootstrapBadgeClass() }}">{{ $order->status->label() }}</span>
                        </div>

                        <p class="text-muted small mb-4">Оформлен {{ $order->created_at?->format('d.m.Y H:i') }}</p>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Товар</th>
                                        <th class="text-center">Кол-во</th>
                                        <th class="text-end">Сумма</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product_name }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">{{ number_format((float) $item->total, 0, ',', ' ') }} ₽</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Доставка ({{ $order->deliveryMethod?->name ?? '—' }})</span>
                            <span>{{ number_format((float) $order->delivery_price, 0, ',', ' ') }} ₽</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 fw-semibold fs-5">
                            <span>Итого</span>
                            <span>{{ $order->grandTotal() }} ₽</span>
                        </div>

                        <hr>

                        <div class="row g-3 small text-muted">
                            <div class="col-md-6">
                                <div><strong class="text-dark">Получатель:</strong> {{ $order->customer_name }}</div>
                                <div><strong class="text-dark">Телефон:</strong> {{ $order->customer_phone }}</div>
                                @if($order->customer_email)
                                    <div><strong class="text-dark">Email:</strong> {{ $order->customer_email }}</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div><strong class="text-dark">Адрес доставки:</strong> {{ $order->delivery_address }}</div>
                                @if($order->comment)
                                    <div><strong class="text-dark">Комментарий:</strong> {{ $order->comment }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

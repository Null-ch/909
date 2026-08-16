@extends('layouts.app')

@section('meta_title', $metaTitle)

@section('breadcrumbs')
    @include('front.partials.breadcrumbs', ['items' => [
        ['title' => 'Личный кабинет', 'url' => route('account.dashboard')],
        ['title' => 'Мои заказы', 'url' => route('account.orders.index')],
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
                        <h2 class="h5 mb-3">Мои заказы</h2>

                        @if($orders->isEmpty())
                            <p class="text-muted mb-0">У вас пока нет заказов. <a href="{{ url('/catalog') }}">Перейти в каталог</a>.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table align-middle mb-3">
                                    <thead>
                                        <tr>
                                            <th>Номер</th>
                                            <th>Дата</th>
                                            <th>Статус</th>
                                            <th class="text-end">Сумма</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $order)
                                            <tr>
                                                <td>{{ $order->order_number }}</td>
                                                <td>{{ $order->created_at?->format('d.m.Y H:i') }}</td>
                                                <td><span class="badge {{ $order->status->bootstrapBadgeClass() }}">{{ $order->status->label() }}</span></td>
                                                <td class="text-end">{{ $order->grandTotal() }} ₽</td>
                                                <td class="text-end">
                                                    <a href="{{ route('account.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Подробнее</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $orders->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

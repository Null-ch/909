@extends('layouts.gentelella')

@section('title', 'Заказ '.$order->order_number)
@section('page', 'orders')
@section('breadcrumb', 'Главная > Заказы > '.$order->order_number)

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Заказ</div>
                <h1 class="page-title">{{ $order->order_number }}</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline">К списку заказов</a>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: var(--admin-gap, 24px);">
        <div class="card-header">
            <div class="card-title">Управление заказом</div>
            <div class="card-subtitle">
                Статус:
                <span class="badge {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
                · Оплата:
                <span class="badge {{ $order->payment_status->badgeClass() }}">{{ $order->payment_status->label() }}</span>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="form-row" style="align-items:flex-end">
                @csrf
                @method('PUT')

                <div class="form-group" style="flex:1">
                    <label class="form-label" for="status">Статус заказа</label>
                    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                        @foreach (\App\Enums\OrderStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(old('status', $order->status->value) === $status->value)>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="flex:1">
                    <label class="form-label" for="payment_status">Статус оплаты</label>
                    <select id="payment_status" name="payment_status" class="form-control @error('payment_status') is-invalid @enderror">
                        @foreach (\App\Enums\PaymentStatus::cases() as $paymentStatus)
                            <option value="{{ $paymentStatus->value }}" @selected(old('payment_status', $order->payment_status->value) === $paymentStatus->value)>
                                {{ $paymentStatus->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('payment_status')
                        <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
            @if ($order->stock_deducted)
                <div class="form-help" style="margin-top:12px">Остатки по этому заказу уже списаны.</div>
            @elseif ($order->status === \App\Enums\OrderStatus::Delivered)
                <div class="form-help" style="margin-top:12px">При статусе «Доставлен» остатки будут списаны автоматически.</div>
            @endif
        </div>
    </div>

    <div class="row col-2">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Покупатель</div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="form-label">Имя</div>
                    <div>{{ $order->customer_name }}</div>
                </div>
                <div class="form-group">
                    <div class="form-label">Телефон</div>
                    <div>{{ $order->customer_phone }}</div>
                </div>
                <div class="form-group">
                    <div class="form-label">Email</div>
                    <div>{{ $order->customer_email ?: '—' }}</div>
                </div>
                @if ($order->user)
                    <div class="form-group">
                        <div class="form-label">Аккаунт</div>
                        <div>{{ $order->user->name }} ({{ $order->user->email }})</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Доставка и оплата</div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="form-label">Адрес доставки</div>
                    <div>{{ $order->delivery_address }}</div>
                </div>
                @if ($order->comment)
                    <div class="form-group">
                        <div class="form-label">Комментарий</div>
                        <div>{{ $order->comment }}</div>
                    </div>
                @endif
                <div class="form-group">
                    <div class="form-label">Способ доставки</div>
                    <div>{{ $order->deliveryMethod?->name ?? '—' }}</div>
                </div>
                <div class="form-group">
                    <div class="form-label">Стоимость доставки</div>
                    <div>{{ number_format((float) $order->delivery_price, 2, '.', ' ') }} ₽</div>
                </div>
                <div class="form-group">
                    <div class="form-label">Статус оплаты</div>
                    <div><span class="badge {{ $order->payment_status->badgeClass() }}">{{ $order->payment_status->label() }}</span></div>
                </div>
                <div class="form-group">
                    <div class="form-label">Дата заказа</div>
                    <div>{{ $order->created_at?->format('d.m.Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: var(--admin-gap, 24px);">
        <div class="card-header">
            <div class="card-title">Товары в заказе</div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>SKU</th>
                            <th>Кол-во</th>
                            <th>Цена</th>
                            <th style="text-align:right">Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->sku }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format((float) $item->price, 2, '.', ' ') }} ₽</td>
                                <td style="text-align:right">{{ number_format((float) $item->total, 2, '.', ' ') }} ₽</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align:right"><strong>Товары:</strong></td>
                            <td style="text-align:right">{{ number_format((float) $order->total_price, 2, '.', ' ') }} ₽</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:right"><strong>Доставка:</strong></td>
                            <td style="text-align:right">{{ number_format((float) $order->delivery_price, 2, '.', ' ') }} ₽</td>
                        </tr>
                        <tr>
                            <td colspan="4" style="text-align:right"><strong>Итого:</strong></td>
                            <td style="text-align:right"><strong>{{ $order->grandTotal() }} ₽</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('meta_title', $metaTitle)

@section('content')
    <div class="order-success py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm p-4 p-md-5 text-center mb-4">
                        <div class="order-success__icon mb-3">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <h1 class="h3 mb-2">Спасибо за заказ!</h1>
                        <p class="text-muted mb-0">
                            Заказ <strong>{{ $order->order_number }}</strong> успешно оформлен.
                            Мы свяжемся с вами в ближайшее время для подтверждения.
                        </p>
                    </div>

                    @if($accountStatus === 'created')
                        <div class="alert alert-success">
                            <strong>Мы создали для вас личный кабинет.</strong>
                            Пароль для входа отправлен на {{ $order->customer_email }}.
                            <a href="{{ route('account.orders.show', $order) }}" class="alert-link">Посмотреть заказ в личном кабинете</a>.
                        </div>
                    @elseif($accountStatus === 'existing_account')
                        <div class="alert alert-info">
                            У вас уже есть аккаунт с email {{ $order->customer_email }}.
                            <a href="{{ route('login') }}" class="alert-link">Войдите</a>, чтобы видеть этот заказ в личном кабинете.
                        </div>
                    @elseif($accountStatus === 'existing_session')
                        <div class="alert alert-info">
                            Заказ сохранён в вашем
                            <a href="{{ route('account.orders.show', $order) }}" class="alert-link">личном кабинете</a>.
                        </div>
                    @endif

                    <div class="card border-0 shadow-sm p-4">
                        <h2 class="h5 mb-3">Детали заказа</h2>

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
                            <span>Итого к оплате</span>
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

                    <div class="text-center mt-4">
                        <a href="{{ url('/catalog') }}" class="btn btn-primary">Продолжить покупки</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

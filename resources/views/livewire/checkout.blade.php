<div class="checkout-page py-4">
    <div class="container">
        @include('front.partials.breadcrumbs', ['items' => [
            ['title' => 'Корзина', 'url' => url('/cart')],
            ['title' => 'Оформление заказа', 'url' => url('/checkout')],
        ]])

        <h1 class="checkout-page__title h2 mb-4">Оформление заказа</h1>

        @if($errorMessage)
            <div class="alert alert-danger">{{ $errorMessage }}</div>
        @endif

        <form wire:submit="placeOrder">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="checkout-card card border-0 shadow-sm p-4 mb-4">
                        <h2 class="h5 mb-3">Контактные данные</h2>

                        <div class="checkout-field mb-3">
                            <label class="form-label">Имя и фамилия</label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="checkout-field mb-3">
                            <label class="form-label">Телефон</label>
                            <input type="tel"
                                   wire:model="phone"
                                   class="form-control js-phone-input @error('phone') is-invalid @enderror"
                                   placeholder="+7 (___) ___-__-__"
                                   inputmode="tel"
                                   autocomplete="tel"
                                   maxlength="18">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="checkout-field">
                            <label class="form-label">Email</label>
                            <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror" @if(auth()->check()) readonly @endif>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @auth
                                <div class="form-text">Заказ будет оформлен на ваш аккаунт.</div>
                            @else
                                <div class="form-text">
                                    Если аккаунта с этим email ещё нет, мы создадим его и вышлем пароль на почту.
                                    Уже зарегистрированы? <a href="{{ route('login') }}">Войдите</a>, чтобы оформить заказ на свой аккаунт.
                                </div>
                            @endauth
                        </div>
                    </div>

                    <div class="checkout-card card border-0 shadow-sm p-4 mb-4">
                        <h2 class="h5 mb-3">Доставка</h2>

                        <div class="mb-3">
                            <label class="form-label">Адрес доставки</label>
                            <textarea wire:model="address" rows="2" class="form-control @error('address') is-invalid @enderror" placeholder="Город, улица, дом, квартира"></textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="checkout-delivery-options">
                            @forelse($deliveryOptions as $option)
                                <label class="checkout-delivery-option d-flex align-items-center gap-3 p-3 mb-2 border rounded
                                    {{ (int) $deliveryMethodId === $option['method']->id ? 'checkout-delivery-option--active' : '' }}
                                    {{ ! $option['available'] ? 'checkout-delivery-option--disabled' : '' }}">
                                    <input type="radio"
                                           class="form-check-input mt-0"
                                           wire:model.live="deliveryMethodId"
                                           value="{{ $option['method']->id }}"
                                           @disabled(! $option['available'])>
                                    <span class="flex-grow-1">
                                        <span class="d-block fw-semibold">{{ $option['method']->name }}</span>
                                        @if($option['method']->description)
                                            <span class="d-block text-muted small">{{ $option['method']->description }}</span>
                                        @endif
                                        @unless($option['available'])
                                            <span class="d-block text-danger small">Недоступно для вашего заказа</span>
                                        @endunless
                                    </span>
                                    <span class="fw-semibold">
                                        {{ $option['available'] ? number_format($option['price'], 0, ',', ' ').' ₽' : '—' }}
                                    </span>
                                </label>
                            @empty
                                <div class="alert alert-warning mb-0">
                                    Не удалось подобрать способ доставки для товаров в корзине. Свяжитесь с нами для уточнения.
                                </div>
                            @endforelse
                            @error('deliveryMethodId') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Комментарий к заказу <span class="text-muted">(необязательно)</span></label>
                            <textarea wire:model="comment" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="cart-summary card border-0 shadow-sm p-4">
                        <h2 class="h5 mb-3">Ваш заказ</h2>

                        <ul class="list-unstyled checkout-summary__items mb-3">
                            @foreach($cartItems as $item)
                                <li class="d-flex justify-content-between small mb-2">
                                    <span class="text-muted">{{ $item->product?->name ?? 'Товар удалён' }} × {{ $item->quantity }}</span>
                                    <span>{{ number_format($item->lineTotal(), 0, ',', ' ') }} ₽</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Товары</span>
                            <span>{{ number_format($itemsTotal, 0, ',', ' ') }} ₽</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Доставка</span>
                            <span>{{ number_format($deliveryPrice, 0, ',', ' ') }} ₽</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 fw-semibold fs-5">
                            <span>Итого</span>
                            <span>{{ number_format($grandTotal, 0, ',', ' ') }} ₽</span>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg" wire:loading.attr="disabled" wire:target="placeOrder">
                            <span wire:loading.remove wire:target="placeOrder">Подтвердить заказ</span>
                            <span wire:loading wire:target="placeOrder">
                                <span class="spinner-border spinner-border-sm me-2"></span>Оформляем...
                            </span>
                        </button>
                        <a href="{{ url('/cart') }}" class="btn btn-link w-100 mt-2">Вернуться в корзину</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

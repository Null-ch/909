<div class="cart-page py-4">
    <div class="container">
        @include('front.partials.breadcrumbs', ['items' => [['title' => 'Корзина', 'url' => url('/cart')]]])

        <h1 class="cart-page__title h2 mb-4">Корзина</h1>

        @if($items->isEmpty())
            <div class="alert alert-light border text-center py-5">
                <i class="fa-solid fa-cart-shopping fa-2x text-muted mb-3"></i>
                <p class="mb-3">Ваша корзина пуста.</p>
                <a href="{{ url('/catalog') }}" class="btn btn-primary">Перейти в каталог</a>
            </div>
        @else
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="cart-page__list card border-0 shadow-sm">
                        @foreach($items as $item)
                            @php
                                $product = $item->product;
                                $image = $product?->mainImage();
                                $imageUrl = $image ? storage_url($image->image_path) : null;
                            @endphp
                            <div class="cart-item d-flex align-items-center gap-3 p-3" wire:key="cart-item-{{ $item->id }}">
                                <a href="{{ $product ? url('/product/'.$product->slug) : '#' }}" class="cart-item__image-link flex-shrink-0">
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="cart-item__image">
                                    @else
                                        <div class="cart-item__placeholder">
                                            <i class="fa-solid fa-leaf"></i>
                                        </div>
                                    @endif
                                </a>

                                <div class="cart-item__info flex-grow-1">
                                    <a href="{{ $product ? url('/product/'.$product->slug) : '#' }}" class="cart-item__title text-decoration-none text-dark fw-semibold">
                                        {{ $product?->name ?? 'Товар удалён' }}
                                    </a>
                                    <div class="text-muted small">{{ number_format((float) $item->price, 0, ',', ' ') }} ₽ / шт.</div>
                                </div>

                                <div class="cart-item__quantity input-group input-group-sm" style="max-width: 130px;">
                                    <button type="button"
                                            class="btn btn-outline-secondary"
                                            wire:click="decrementItem({{ $item->id }})"
                                            aria-label="Уменьшить количество">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <input type="text"
                                           class="form-control text-center"
                                           value="{{ $item->quantity }}"
                                           readonly>
                                    <button type="button"
                                            class="btn btn-outline-secondary"
                                            wire:click="incrementItem({{ $item->id }})"
                                            aria-label="Увеличить количество"
                                            @if($product && $item->quantity >= $product->quantity) disabled @endif>
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>

                                <div class="cart-item__total fw-semibold text-end" style="min-width: 110px;">
                                    {{ number_format($item->lineTotal(), 0, ',', ' ') }} ₽
                                </div>

                                <button type="button"
                                        class="btn btn-link text-danger cart-item__remove"
                                        @click.prevent="window.confirmAction('Удалить товар из корзины?', () => $wire.removeItem({{ $item->id }}))"
                                        aria-label="Удалить товар">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="cart-summary card border-0 shadow-sm p-4">
                        <h2 class="h5 mb-3">Итого</h2>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Товаров</span>
                            <span>{{ $items->sum('quantity') }} шт.</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 fw-semibold fs-5">
                            <span>Сумма</span>
                            <span>{{ number_format($total, 0, ',', ' ') }} ₽</span>
                        </div>
                        <a href="{{ url('/checkout') }}" class="btn btn-primary w-100 btn-lg">
                            Оформить заказ
                        </a>
                        <a href="{{ url('/catalog') }}" class="btn btn-link w-100 mt-2">
                            Продолжить покупки
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

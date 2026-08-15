<div class="site-cart" x-data="{ open: false }" @click.outside="open = false">
    <a href="{{ url('/cart') }}"
       class="site-cart__toggle btn btn-outline-primary position-relative"
       @mouseenter="open = true"
       @click.prevent="open = !open">
        <i class="fa-solid fa-cart-shopping"></i>
        @if($itemsCount > 0)
            <span class="site-cart__badge">{{ $itemsCount }}</span>
        @endif
        <span class="site-cart__label d-none d-md-inline ms-2">Корзина</span>
    </a>

    <div class="site-cart__dropdown"
         x-show="open"
         x-transition
         @mouseenter="open = true"
         @mouseleave="open = false"
         style="display: none;">
        <div class="site-cart__dropdown-header">Корзина</div>
        <div class="site-cart__dropdown-body">
            @if($itemsCount > 0)
                <div class="site-cart__summary">
                    <span>Товаров: <strong>{{ $itemsCount }}</strong></span>
                    <span>Итого: <strong>{{ number_format($totalPrice, 0, ',', ' ') }} ₽</strong></span>
                </div>
            @else
                <p class="text-muted mb-0 small">Корзина пуста</p>
            @endif
        </div>
        <div class="site-cart__dropdown-footer">
            <a href="{{ url('/cart') }}" class="btn btn-sm btn-outline-primary w-100 mb-2">В корзину</a>
            @if($itemsCount > 0)
                <a href="{{ url('/checkout') }}" class="btn btn-sm btn-primary w-100">Оформить</a>
            @endif
        </div>
    </div>
</div>

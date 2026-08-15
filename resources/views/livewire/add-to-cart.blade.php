<div>
    <div class="product-purchase__quantity d-flex align-items-center mb-3">
        <span class="me-3 text-muted small">Количество:</span>
        <div class="input-group product-purchase__counter" style="max-width: 140px;">
            <button type="button"
                    class="btn btn-outline-secondary"
                    wire:click="decrement"
                    aria-label="Уменьшить количество">
                <i class="fa-solid fa-minus"></i>
            </button>
            <input type="text"
                   class="form-control text-center"
                   value="{{ $quantity }}"
                   readonly
                   aria-label="Количество">
            <button type="button"
                    class="btn btn-outline-secondary"
                    wire:click="increment"
                    aria-label="Увеличить количество"
                    @if($quantity >= $product->quantity) disabled @endif>
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
        <span class="ms-3 text-muted small">В наличии: {{ $product->quantity }}</span>
    </div>

    <button type="button"
            wire:click="addToCart"
            class="btn btn-primary btn-lg w-100"
            @if($product->quantity < 1) disabled @endif>
        <i class="fa-solid fa-cart-plus me-2"></i>
        {{ $product->quantity > 0 ? 'В корзину' : 'Нет в наличии' }}
    </button>

    @if($successMessage)
        <div class="alert alert-success mt-3 mb-0 py-2 small">
            <i class="fa-solid fa-check-circle me-1"></i>{{ $successMessage }}
        </div>
    @endif
</div>

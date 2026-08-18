@php
    $image = $product->mainImage();
    $imageUrl = $image ? storage_url($image->image_path) : null;
@endphp

<div class="product-card card h-100 border-0 shadow-sm">
    <a href="{{ url('/product/'.$product->slug) }}" class="product-card__image-link">
        <div class="product-card__image-wrap">
            @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="product-card__image">
            @else
                <div class="product-card__placeholder">
                    <i class="fa-solid fa-leaf"></i>
                </div>
            @endif
            @if($product->is_featured)
                <span class="product-card__badge">Хит</span>
            @endif
        </div>
    </a>

    <div class="card-body d-flex flex-column">
        <div class="product-card__sku text-muted small mb-1">{{ $product->sku }}</div>
        <h3 class="product-card__title h6 mb-2">
            <a href="{{ url('/product/'.$product->slug) }}" class="text-decoration-none text-dark">
                {{ $product->name }}
            </a>
        </h3>

        @if($product->short_description)
            <p class="product-card__description text-muted small mb-3">{{ $product->short_description }}</p>
        @endif

        <div class="mt-auto">
            <div class="product-card__price mb-3">
                @if($product->hasPrice())
                    <span class="product-card__price-current">{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                    @if($product->old_price)
                        <span class="product-card__price-old">{{ number_format($product->old_price, 0, ',', ' ') }} ₽</span>
                    @endif
                @else
                    <span class="product-card__price-inquiry">Стоимость уточняйте у менеджера</span>
                @endif
            </div>

            <button type="button" wire:click="addToCart" class="btn btn-primary w-100">
                <i class="fa-solid fa-cart-plus me-1"></i>В корзину
            </button>
        </div>
    </div>
</div>

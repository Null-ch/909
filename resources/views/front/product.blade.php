@extends('layouts.app')

@section('meta_title', $metaTitle)
@section('meta_description', $metaDescription)

@section('breadcrumbs')
    @include('front.partials.breadcrumbs', ['items' => $breadcrumbs])
@endsection

@section('content')
    @php
        $galleryImages = $product->images;
        $defaultImage = $product->mainImage();
        $defaultImageUrl = $defaultImage ? storage_url($defaultImage->image_path) : null;
    @endphp

    <div class="product-page py-4">
        <div class="container">
            <div class="row g-4 g-lg-5">
                <div class="col-lg-6">
                    <div class="product-gallery card border-0 shadow-sm"
                         x-data="{ activeImage: @js($defaultImageUrl) }">
                        <div class="product-gallery__main">
                            <template x-if="activeImage">
                                <img :src="activeImage"
                                     alt="{{ $product->name }}"
                                     class="product-gallery__image">
                            </template>
                            <template x-if="!activeImage">
                                <div class="product-gallery__placeholder">
                                    <i class="fa-solid fa-leaf"></i>
                                </div>
                            </template>
                        </div>

                        @if($galleryImages->count() > 1)
                            <div class="product-gallery__thumbs">
                                @foreach($galleryImages as $image)
                                    @php $thumbUrl = storage_url($image->image_path); @endphp
                                    <button type="button"
                                            class="product-gallery__thumb"
                                            :class="{ 'is-active': activeImage === @js($thumbUrl) }"
                                            @click="activeImage = @js($thumbUrl)">
                                        <img src="{{ $thumbUrl }}" alt="{{ $product->name }}">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="product-page__info">
                        @if($product->is_featured)
                            <span class="badge bg-warning text-dark mb-2">Хит продаж</span>
                        @endif

                        <h1 class="product-page__title h2">{{ $product->name }}</h1>

                        <div class="product-page__sku text-muted mb-3">
                            Артикул: <strong>{{ $product->sku }}</strong>
                        </div>

                        <div class="product-page__price mb-4">
                            <span class="product-page__price-current">
                                {{ number_format($product->price, 0, ',', ' ') }} ₽
                            </span>
                            @if($product->old_price)
                                <span class="product-page__price-old">
                                    {{ number_format($product->old_price, 0, ',', ' ') }} ₽
                                </span>
                            @endif
                        </div>

                        @if($product->short_description)
                            <p class="product-page__short text-muted mb-4">{{ $product->short_description }}</p>
                        @endif

                        <livewire:add-to-cart :product="$product" :key="'add-to-cart-'.$product->id" />

                        @if($product->categories->isNotEmpty())
                            <div class="product-page__categories mt-4">
                                <span class="text-muted small me-2">Категории:</span>
                                @foreach($product->categories as $category)
                                    <a href="{{ url('/category/'.$category->slug) }}"
                                       class="badge bg-light text-dark text-decoration-none me-1">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($product->attributes->isNotEmpty())
                <section class="product-attributes mt-5">
                    <h2 class="h4 section-title mb-3">Характеристики</h2>
                    <div class="table-responsive">
                        <table class="table table-bordered product-attributes__table mb-0">
                            <tbody>
                                @foreach($product->attributes as $attribute)
                                    <tr>
                                        <th scope="row">{{ $attribute->attribute_name }}</th>
                                        <td>{{ $attribute->attribute_value }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif

            @if($product->editorDescription())
                <section class="product-description mt-5">
                    <h2 class="h4 section-title mb-3">Описание</h2>
                    <div class="product-description__content">
                        {!! $product->editorDescription() !!}
                    </div>
                </section>
            @endif
        </div>
    </div>

    <livewire:related-products :product="$product" :key="'related-products-'.$product->id" />
@endsection

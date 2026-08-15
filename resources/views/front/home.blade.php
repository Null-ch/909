@extends('layouts.app')

@section('meta_title', $metaTitle)
@section('meta_description', $metaDescription)

@section('content')
    {{-- Hero-слайдер --}}
    @if($banners->isNotEmpty())
        <section class="home-hero">
            <div class="swiper home-hero__slider"
                 x-data
                 x-init="
                    new Swiper($el, {
                        modules: [SwiperModules.Navigation, SwiperModules.Pagination, SwiperModules.Autoplay],
                        loop: {{ $banners->count() > 1 ? 'true' : 'false' }},
                        autoplay: { delay: 5000, disableOnInteraction: false },
                        pagination: { el: '.home-hero__pagination', clickable: true },
                        navigation: {
                            nextEl: '.home-hero__next',
                            prevEl: '.home-hero__prev',
                        },
                    })
                 ">
                <div class="swiper-wrapper">
                    @foreach($banners as $banner)
                        <div class="swiper-slide">
                            <div class="home-hero__slide @unless($banner->image) home-hero__slide--gradient @endunless"
                                 @if($banner->image) style="background-image: url('{{ storage_url($banner->image) }}')" @endif>
                                <div class="container">
                                    <div class="home-hero__content">
                                        <h1 class="home-hero__title">{{ $banner->title }}</h1>
                                        @if($banner->subtitle)
                                            <p class="home-hero__subtitle">{{ $banner->subtitle }}</p>
                                        @endif
                                        @if($banner->link && $banner->button_text)
                                            <a href="{{ url($banner->link) }}" class="btn btn-accent btn-lg">
                                                <i class="fa-solid fa-leaf me-2"></i>{{ $banner->button_text }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($banners->count() > 1)
                    <div class="home-hero__pagination swiper-pagination"></div>
                    <button type="button" class="home-hero__nav home-hero__prev" aria-label="Предыдущий слайд">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button type="button" class="home-hero__nav home-hero__next" aria-label="Следующий слайд">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                @endif
            </div>
        </section>
    @endif

    {{-- Преимущества --}}
    @if(!empty($benefits))
        <section class="home-benefits py-5">
            <div class="container">
                <div class="home-benefits__grid">
                    @foreach($benefits as $benefit)
                        <div class="home-benefits__col">
                            <div class="home-benefits__item text-center h-100">
                                <div class="home-benefits__icon">
                                    <i class="fa-solid {{ benefit_icon($benefit['icon'] ?? 'leaf') }}"></i>
                                </div>
                                <h5 class="home-benefits__title">{{ $benefit['title'] ?? '' }}</h5>
                                @if(!empty($benefit['text']))
                                    <p class="home-benefits__text text-muted small mb-0">{{ $benefit['text'] }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Категории-плитки --}}
    @if($categories->isNotEmpty())
        <section class="home-categories py-5 bg-light-green">
            <div class="container">
                <h2 class="section-title text-center">Категории товаров</h2>
                <div class="row g-4">
                    @foreach($categories as $category)
                        <div class="col-lg-4 col-md-6">
                            <a href="{{ url('/category/'.$category->slug) }}" class="home-categories__card text-decoration-none">
                                <div class="home-categories__image-wrap">
                                    @if($category->image)
                                        <img src="{{ storage_url($category->image) }}" alt="{{ $category->name }}" class="home-categories__image">
                                    @else
                                        <div class="home-categories__placeholder">
                                            <i class="fa-solid fa-seedling"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="home-categories__body">
                                    <h3 class="home-categories__title">{{ $category->name }}</h3>
                                    @if($category->plainDescription())
                                        <p class="home-categories__text">{{ Str::limit($category->plainDescription(), 90) }}</p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Хиты продаж --}}
    @if($featuredProducts->isNotEmpty())
        <section class="home-featured py-5">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <h2 class="section-title mb-0">Хиты продаж</h2>
                    <a href="{{ url('/catalog') }}" class="btn btn-outline-primary">Весь каталог</a>
                </div>
                <div class="row g-4">
                    @foreach($featuredProducts as $product)
                        <div class="col-xl-3 col-lg-4 col-md-6" wire:key="product-{{ $product->id }}">
                            <livewire:product-card :product="$product" :key="'product-card-'.$product->id" />
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- SEO-текст --}}
    @if($seoText)
        <section class="home-seo py-5 bg-light">
            <div class="container">
                <div class="home-seo__content">
                    {!! $seoText !!}
                </div>
            </div>
        </section>
    @endif
@endsection

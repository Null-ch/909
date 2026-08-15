<div class="site-topbar">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 py-2">
            <div class="d-flex flex-wrap align-items-center gap-3 site-topbar__contacts">
                @if($contactPhone)
                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $contactPhone) }}" class="site-topbar__link">
                        <i class="fa-solid fa-phone me-1"></i>{{ $contactPhone }}
                    </a>
                @endif
                @if($contactEmail)
                    <a href="mailto:{{ $contactEmail }}" class="site-topbar__link">
                        <i class="fa-solid fa-envelope me-1"></i>{{ $contactEmail }}
                    </a>
                @endif
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="site-topbar__social d-none d-md-flex align-items-center gap-2">
                    @if($socialVk)
                        <a href="{{ $socialVk }}" target="_blank" rel="noopener" class="site-topbar__social-link" title="ВКонтакте">
                            <i class="fa-brands fa-vk"></i>
                        </a>
                    @endif
                    @if($socialTelegram)
                        <a href="{{ $socialTelegram }}" target="_blank" rel="noopener" class="site-topbar__social-link" title="Telegram">
                            <i class="fa-brands fa-telegram"></i>
                        </a>
                    @endif
                    @if($socialWhatsapp)
                        <a href="{{ str_starts_with($socialWhatsapp, 'http') ? $socialWhatsapp : 'https://wa.me/'.preg_replace('/\D/', '', $socialWhatsapp) }}"
                           target="_blank" rel="noopener" class="site-topbar__social-link" title="WhatsApp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    @endif
                </div>

                <a href="{{ url('/account') }}" class="site-topbar__link d-none d-sm-inline-flex align-items-center">
                    <i class="fa-solid fa-user me-1"></i>Личный кабинет
                </a>
            </div>
        </div>
    </div>
</div>

<header class="site-header">
    <div class="container">
        <div class="row align-items-center g-3 py-3">
            <div class="col-lg-3 col-md-4 col-8">
                <a href="{{ url('/') }}" class="site-logo d-inline-flex align-items-center text-decoration-none">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $shopName }}" class="site-logo__image">
                    @else
                        <span class="site-logo__text">
                            <i class="fa-solid fa-leaf me-2"></i>{{ $shopName }}
                        </span>
                    @endif
                </a>
            </div>

            <div class="col-lg-6 d-none d-lg-block">
                <form action="{{ url('/search') }}" method="GET" class="site-search" role="search">
                    <div class="input-group">
                        <input type="search"
                               name="q"
                               class="form-control site-search__input"
                               placeholder="Поиск товаров..."
                               value="{{ request('q') }}"
                               aria-label="Поиск товаров">
                        <button class="btn btn-primary site-search__btn" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="col-lg-3 col-md-8 col-4">
                <div class="d-flex align-items-center justify-content-end gap-2 gap-md-3">
                    <button type="button"
                            class="btn btn-outline-secondary d-lg-none site-header__toggle"
                            data-bs-toggle="collapse"
                            data-bs-target="#siteMobileSearch"
                            aria-expanded="false"
                            aria-controls="siteMobileSearch">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>

                    <livewire:cart-icon />

                    <button type="button"
                            class="btn btn-outline-secondary d-lg-none site-header__toggle"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#siteMobileMenu"
                            aria-controls="siteMobileMenu">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="collapse d-lg-none pb-3" id="siteMobileSearch">
            <form action="{{ url('/search') }}" method="GET" class="site-search" role="search">
                <div class="input-group">
                    <input type="search"
                           name="q"
                           class="form-control site-search__input"
                           placeholder="Поиск товаров..."
                           value="{{ request('q') }}">
                    <button class="btn btn-primary site-search__btn" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</header>

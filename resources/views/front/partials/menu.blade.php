<nav class="site-nav d-none d-lg-block" aria-label="Основное меню">
    <div class="container">
        <ul class="site-nav__list">
            <li class="site-nav__item">
                <a href="{{ url('/') }}" class="site-nav__link {{ request()->is('/') ? 'is-active' : '' }}">
                    <i class="fa-solid fa-house me-1"></i>Главная
                </a>
            </li>

            <li class="site-nav__item {{ $navCategories->isNotEmpty() ? 'has-dropdown' : '' }}"
                @if($navCategories->isNotEmpty())
                    x-data="{ open: false }"
                    @mouseenter="open = true"
                    @mouseleave="open = false"
                @endif>
                <a href="{{ url('/catalog') }}"
                   class="site-nav__link {{ request()->is('catalog*') || request()->is('category*') ? 'is-active' : '' }}">
                    Каталог
                    @if($navCategories->isNotEmpty())
                        <i class="fa-solid fa-chevron-down ms-1 small"></i>
                    @endif
                </a>

                @if($navCategories->isNotEmpty())
                    <ul class="site-nav__dropdown" x-show="open" x-transition.opacity>
                        @foreach($navCategories as $category)
                            <li>
                                <a href="{{ url('/category/'.$category->slug) }}" class="site-nav__dropdown-link">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>

            <li class="site-nav__item">
                <a href="{{ url('/about') }}" class="site-nav__link {{ request()->is('about') ? 'is-active' : '' }}">
                    О компании
                </a>
            </li>

            <li class="site-nav__item">
                <a href="{{ url('/contacts') }}" class="site-nav__link {{ request()->is('contacts') ? 'is-active' : '' }}">
                    Контакты
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="offcanvas offcanvas-start site-offcanvas" tabindex="-1" id="siteMobileMenu" aria-labelledby="siteMobileMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="siteMobileMenuLabel">{{ $shopName }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Закрыть"></button>
    </div>
    <div class="offcanvas-body">
        <div class="list-group list-group-flush">
            <a href="{{ url('/') }}" class="list-group-item list-group-item-action">Главная</a>
            <a href="{{ url('/catalog') }}" class="list-group-item list-group-item-action">Каталог</a>

            @foreach($navCategories as $category)
                <a href="{{ url('/category/'.$category->slug) }}" class="list-group-item list-group-item-action fw-semibold">
                    {{ $category->name }}
                </a>
                @foreach($category->children as $child)
                    <a href="{{ url('/category/'.$child->slug) }}" class="list-group-item list-group-item-action ps-4">
                        {{ $child->name }}
                    </a>
                @endforeach
            @endforeach

            <a href="{{ url('/about') }}" class="list-group-item list-group-item-action">О компании</a>
            <a href="{{ url('/contacts') }}" class="list-group-item list-group-item-action">Контакты</a>

            @auth
                <a href="{{ route('account.dashboard') }}" class="list-group-item list-group-item-action">
                    <i class="fa-solid fa-user me-2"></i>Личный кабинет
                </a>
            @else
                <a href="{{ route('login') }}" class="list-group-item list-group-item-action">
                    <i class="fa-solid fa-user me-2"></i>Войти
                </a>
            @endauth
        </div>

        @if($contactPhone)
            <div class="mt-4 p-3 bg-light rounded">
                <div class="text-muted small mb-1">Телефон</div>
                <a href="tel:{{ preg_replace('/[^\d+]/', '', $contactPhone) }}" class="fw-semibold text-decoration-none">
                    {{ $contactPhone }}
                </a>
            </div>
        @endif
    </div>
</div>

@php
    $activePage = trim($__env->yieldContent('page'));
@endphp

<aside class="sidebar" aria-label="Основная навигация">
    <div class="sidebar-brand">
        <div class="brand-icon">{{ strtoupper(substr(config('app.name', 'A'), 0, 1)) }}</div>
        <div class="brand-name">{{ config('app.name') }}</div>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-group">
            <div class="nav-label">Продажи</div>
            <a class="nav-link{{ str_starts_with($activePage, 'orders') ? ' active' : '' }}" href="{{ route('admin.orders.index') }}" @if (str_starts_with($activePage, 'orders')) aria-current="page" @endif>
                <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M6 2h9l3 3v13a1 1 0 01-1 1H6a1 1 0 01-1-1V3a1 1 0 011-1z"/>
                    <path d="M14 2v4h4M8 10h8M8 14h5"/>
                </svg>
                <span class="nav-text">Заказы</span>
            </a>
            <a class="nav-link{{ str_starts_with($activePage, 'carts') ? ' active' : '' }}" href="{{ route('admin.carts.index') }}" @if (str_starts_with($activePage, 'carts')) aria-current="page" @endif>
                <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="9" cy="20" r="1"/><circle cx="17" cy="20" r="1"/>
                    <path d="M2 3h2l2.4 12.2a1 1 0 001 .8h9.7a1 1 0 00.98-.8L20 7H6"/>
                </svg>
                <span class="nav-text">Корзины</span>
            </a>
            <a class="nav-link{{ str_starts_with($activePage, 'delivery-methods') ? ' active' : '' }}" href="{{ route('admin.delivery-methods.index') }}" @if (str_starts_with($activePage, 'delivery-methods')) aria-current="page" @endif>
                <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M1 6h11v9H1zM12 8h5l3 3v4h-8V8z"/>
                    <circle cx="5" cy="17" r="2"/><circle cx="17" cy="17" r="2"/>
                </svg>
                <span class="nav-text">Доставка</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-label">Каталог</div>

            <a class="nav-link{{ str_starts_with($activePage, 'categories') ? ' active' : '' }}" href="{{ route('admin.categories.index') }}" @if (str_starts_with($activePage, 'categories')) aria-current="page" @endif>
                <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 7a2 2 0 012-2h4l2 2h7a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                </svg>
                <span class="nav-text">Категории</span>
            </a>
            <a class="nav-link{{ str_starts_with($activePage, 'products') ? ' active' : '' }}" href="{{ route('admin.products.index') }}" @if (str_starts_with($activePage, 'products')) aria-current="page" @endif>
                <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M6 2h9l3 3v13a1 1 0 01-1 1H6a1 1 0 01-1-1V3a1 1 0 011-1z"/>
                    <path d="M14 2v4h4M8 10h8M8 14h5"/>
                </svg>
                <span class="nav-text">Товары</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-label">Управление</div>
            <a class="nav-link{{ $activePage === 'dashboard' ? ' active' : '' }}" href="{{ route('admin.dashboard') }}" @if ($activePage === 'dashboard') aria-current="page" @endif>
                <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="3" width="7" height="7" rx="1.5" />
                    <rect x="14" y="3" width="7" height="4" rx="1.5" />
                    <rect x="3" y="14" width="7" height="7" rx="1.5" />
                    <rect x="14" y="10" width="7" height="11" rx="1.5" />
                </svg>
                <span class="nav-text">Панель управления</span>
            </a>
            <a class="nav-link{{ str_starts_with($activePage, 'users') ? ' active' : '' }}" href="{{ route('admin.users.index') }}" @if (str_starts_with($activePage, 'users')) aria-current="page" @endif>
                <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="8" r="4" />
                    <path d="M5 20c0-3.9 3.1-7 7-7s7 3.1 7 7" />
                </svg>
                <span class="nav-text">Пользователи</span>
            </a>
            <a class="nav-link{{ str_starts_with($activePage, 'settings') ? ' active' : '' }}" href="{{ route('admin.settings.edit') }}" @if (str_starts_with($activePage, 'settings')) aria-current="page" @endif>
                <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                </svg>
                <span class="nav-text">Настройки</span>
            </a>
            <a class="nav-link{{ str_starts_with($activePage, 'activity-logs') ? ' active' : '' }}" href="{{ route('admin.activity-logs.index') }}" @if (str_starts_with($activePage, 'activity-logs')) aria-current="page" @endif>
                <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 8v4l3 3M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="nav-text">Журнал действий</span>
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <a href="{{ route('admin.profile.show') }}" class="sidebar-user" style="text-decoration:none;color:inherit">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}<span class="online"></span></div>
            <div class="sidebar-user-info">
                <div class="name">{{ auth()->user()->name }}</div>
                <div class="role">{{ auth()->user()->role === \App\Enums\UserRole::Admin ? 'Администратор' : 'Пользователь' }}</div>
            </div>
        </a>
    </div>
</aside>

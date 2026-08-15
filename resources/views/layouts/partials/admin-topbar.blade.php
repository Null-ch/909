@php
    $breadcrumbRaw = trim($__env->yieldContent('breadcrumb', 'Главная'));
    $breadcrumbRaw = html_entity_decode($breadcrumbRaw, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $breadcrumbs = array_values(array_filter(array_map('trim', preg_split('/\s*>\s*/', $breadcrumbRaw) ?: [])));

    $breadcrumbRoutes = [
        'Главная' => route('admin.dashboard'),
        'Пользователи' => route('admin.users.index'),
        'Категории' => route('admin.categories.index'),
        'Товары' => route('admin.products.index'),
        'Профиль' => route('admin.profile.show'),
    ];
@endphp

<header class="topbar">
    <div class="topbar-left">
        <button class="sidebar-toggle" type="button" aria-label="Открыть меню" aria-controls="sidebar" aria-expanded="false">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <nav class="breadcrumb" aria-label="Навигация">
            @foreach ($breadcrumbs as $crumb)
                @if (! $loop->first)
                    <span class="sep" aria-hidden="true">›</span>
                @endif
                @if ($loop->last)
                    <span class="current" aria-current="page">{{ $crumb }}</span>
                @elseif (isset($breadcrumbRoutes[$crumb]))
                    <a href="{{ $breadcrumbRoutes[$crumb] }}">{{ $crumb }}</a>
                @else
                    <span>{{ $crumb }}</span>
                @endif
            @endforeach
        </nav>
    </div>

    <div class="topbar-right">
        <button class="tb-btn theme-toggle" type="button" title="Переключить тему" aria-label="Переключить тему" aria-pressed="false">
            <svg class="theme-icon-light" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <circle cx="12" cy="12" r="4"/>
                <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
            </svg>
            <svg class="theme-icon-dark" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
            </svg>
        </button>

        <form method="POST" action="{{ route('admin.logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="tb-btn" title="Выйти" aria-label="Выйти">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </button>
        </form>

        <a
            href="{{ route('admin.profile.show') }}"
            class="tb-avatar"
            title="Мой профиль"
            aria-label="Мой профиль"
        >{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</a>
    </div>
</header>

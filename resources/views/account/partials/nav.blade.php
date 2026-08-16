@php
    $active = request()->route()->getName();
@endphp

<div class="account-nav card border-0 shadow-sm">
    <div class="list-group list-group-flush">
        <a href="{{ route('account.dashboard') }}" class="list-group-item list-group-item-action {{ $active === 'account.dashboard' ? 'active' : '' }}">
            <i class="fa-solid fa-gauge me-2"></i>Обзор
        </a>
        <a href="{{ route('account.orders.index') }}" class="list-group-item list-group-item-action {{ str_starts_with($active, 'account.orders') ? 'active' : '' }}">
            <i class="fa-solid fa-bag-shopping me-2"></i>Мои заказы
        </a>
        <a href="{{ route('account.profile.edit') }}" class="list-group-item list-group-item-action {{ $active === 'account.profile.edit' ? 'active' : '' }}">
            <i class="fa-solid fa-user me-2"></i>Профиль
        </a>
    </div>
    <div class="p-3 border-top">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                <i class="fa-solid fa-right-from-bracket me-1"></i>Выйти
            </button>
        </form>
    </div>
</div>

@unless(auth()->user()->hasVerifiedEmail())
    <div class="alert alert-warning mt-3">
        Email не подтверждён.
        <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link p-0 align-baseline">Отправить письмо повторно</button>
        </form>
    </div>
@endunless

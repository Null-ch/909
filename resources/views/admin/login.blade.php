@extends('layouts.gentelella-auth')

@section('title', 'Вход в админ-панель')

@section('content')
    <div class="auth-page">
        <div class="auth-card">
            <div class="auth-brand">
                <div class="brand-icon">{{ strtoupper(substr(config('app.name', 'A'), 0, 1)) }}</div>
                <div class="brand-name">{{ config('app.name') }}</div>
            </div>

            <div class="auth-title">Добро пожаловать</div>
            <div class="auth-subtitle">Войдите, чтобы продолжить в админ-панель.</div>

            @if ($errors->any())
                <div class="alert alert-danger" style="margin-bottom: 16px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <div class="input-group">
                        <svg class="input-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="12" height="10" rx="1.5"/><path d="M2 5l6 4 6-4"/></svg>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="admin@example.com"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Пароль</label>
                    <div class="input-group">
                        <svg class="input-icon" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="7" width="10" height="7" rx="1.5"/><path d="M5 7V5a3 3 0 016 0v2"/></svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="••••••••"
                            required
                        >
                    </div>
                </div>

                <div class="auth-actions">
                    <label class="form-check">
                        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}> Запомнить меня
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;height:38px">
                    Войти
                </button>
            </form>
        </div>
    </div>
@endsection

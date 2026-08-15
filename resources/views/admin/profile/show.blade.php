@extends('layouts.gentelella')

@section('title', 'Мой профиль')
@section('page', 'profile')
@section('breadcrumb', 'Главная > Профиль')

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Аккаунт</div>
                <h1 class="page-title">Мой профиль</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">На главную</a>
            </div>
        </div>
    </div>

    <div class="row col-4-8">
        <div class="card">
            <div class="card-body" style="text-align:center;padding:24px 16px">
                <div style="width:96px;height:96px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--primary-dk));margin:0 auto 12px;display:flex;align-items:center;justify-content:center;color:white;font-size:32px;font-weight:600">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div style="font-size:16px;font-weight:600;color:var(--text)">{{ $user->name }}</div>
                <div style="font-size:12.5px;color:var(--text-muted);margin-top:2px">
                    {{ $user->role === \App\Enums\UserRole::Admin ? 'Администратор' : 'Пользователь' }}
                </div>
                <div style="font-size:12px;color:var(--text-muted);margin-top:8px">
                    В системе с {{ $user->created_at->format('d.m.Y') }}
                </div>
            </div>
            <div style="border-top:1px solid var(--border-color-light);padding:16px">
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:4px">Email</div>
                <div style="font-size:14px;color:var(--text);word-break:break-all">{{ $user->email }}</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Личные данные</div>
                    <div class="card-subtitle">Обновите имя, email и пароль.</div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label" for="name">Имя <span class="required">*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}"
                            required
                        >
                        @error('name')
                            <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email <span class="required">*</span></label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}"
                            required
                        >
                        @error('email')
                            <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="password">Новый пароль</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                autocomplete="new-password"
                            >
                            <div class="form-help">Оставьте пустым, если не хотите менять пароль.</div>
                            @error('password')
                                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Подтверждение пароля</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control"
                                autocomplete="new-password"
                            >
                        </div>
                    </div>

                    <div class="form-actions right">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Отмена</a>
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

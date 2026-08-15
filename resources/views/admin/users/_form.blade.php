@php
    $isEdit = isset($user);
@endphp

<div class="form-group">
    <label class="form-label" for="name">Имя <span class="required">*</span></label>
    <input
        type="text"
        id="name"
        name="name"
        class="form-control @error('name') is-invalid @enderror"
        value="{{ old('name', $user->name ?? '') }}"
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
        value="{{ old('email', $user->email ?? '') }}"
        required
    >
    @error('email')
        <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
    @enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label" for="password">
            Пароль @if (! $isEdit)<span class="required">*</span>@endif
        </label>
        <input
            type="password"
            id="password"
            name="password"
            class="form-control @error('password') is-invalid @enderror"
            @if (! $isEdit) required @endif
            autocomplete="new-password"
        >
        @if ($isEdit)
            <div class="form-help">Оставьте пустым, если не хотите менять пароль.</div>
        @endif
        @error('password')
            <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="password_confirmation">
            Подтверждение пароля @if (! $isEdit)<span class="required">*</span>@endif
        </label>
        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            class="form-control"
            @if (! $isEdit) required @endif
            autocomplete="new-password"
        >
    </div>
</div>

<div class="form-group">
    <label class="form-label" for="role">Роль <span class="required">*</span></label>
    <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
        @foreach ($roles as $role)
            <option
                value="{{ $role->value }}"
                @selected(old('role', $user->role->value ?? '') === $role->value)
            >
                {{ $role === \App\Enums\UserRole::Admin ? 'Администратор' : 'Пользователь' }}
            </option>
        @endforeach
    </select>
    @error('role')
        <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
    @enderror
</div>

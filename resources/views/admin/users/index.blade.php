@extends('layouts.gentelella')

@section('title', 'Пользователи')
@section('page', 'users')
@section('breadcrumb', 'Главная > Пользователи')

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Управление</div>
                <h1 class="page-title">Пользователи</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.export.users') }}" class="btn btn-outline">Экспорт в Excel</a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M8 2v12M2 8h12"/>
                    </svg>
                    Добавить пользователя
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Все пользователи</h3>
            <span style="color: var(--text-muted); font-size: 14px;">{{ $users->total() }} записей</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Роль</th>
                            <th>Создан</th>
                            <th style="text-align: right;">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($user->role === \App\Enums\UserRole::Admin)
                                        <span class="badge badge-teal">Администратор</span>
                                    @else
                                        <span class="badge badge-blue">Пользователь</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; gap: 8px;">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline btn-sm">Изменить</a>
                                        @if ($user->id !== auth()->id())
                                            <form
                                                method="POST"
                                                action="{{ route('admin.users.destroy', $user) }}"
                                                data-confirm="Удалить пользователя {{ $user->name }}?"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline btn-sm" style="color: var(--danger);">Удалить</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 32px;">
                                    Пользователи не найдены.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($users->hasPages())
            <div class="card-footer" style="display: flex; justify-content: center;">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection

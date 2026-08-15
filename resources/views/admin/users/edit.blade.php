@extends('layouts.gentelella')

@section('title', 'Редактирование пользователя')
@section('page', 'users-edit')
@section('breadcrumb', 'Главная > Пользователи > Редактирование')

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Пользователи</div>
                <h1 class="page-title">{{ $user->name }}</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Назад к списку</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Редактирование</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')
                @include('admin.users._form', ['user' => $user])

                <div style="display: flex; gap: 8px; margin-top: 8px;">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection

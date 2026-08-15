@extends('layouts.gentelella')

@section('title', 'Новый пользователь')
@section('page', 'users-create')
@section('breadcrumb', 'Главная > Пользователи > Создание')

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Пользователи</div>
                <h1 class="page-title">Новый пользователь</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Назад к списку</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Данные пользователя</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                @include('admin.users._form')

                <div style="display: flex; gap: 8px; margin-top: 8px;">
                    <button type="submit" class="btn btn-primary">Создать</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection

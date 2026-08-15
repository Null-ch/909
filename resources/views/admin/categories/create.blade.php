@extends('layouts.gentelella')

@section('title', 'Новая категория')
@section('page', 'categories-create')
@section('breadcrumb', 'Главная > Каталог > Категории > Создание')

@push('scripts')
    @vite(['resources/js/admin-categories-form.js'])
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Категории</div>
                <h1 class="page-title">Новая категория</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Назад к списку</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Данные категории</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.categories._form')

                <div style="display:flex;gap:8px;margin-top:8px">
                    <button type="submit" class="btn btn-primary">Создать</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.gentelella')

@section('title', 'Новый баннер')
@section('page', 'banners-create')
@section('breadcrumb', 'Главная > Главная страница > Баннеры > Создание')

@push('scripts')
    @vite(['resources/js/admin-banners-form.js'])
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Баннеры</div>
                <h1 class="page-title">Новый баннер</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.banners.index') }}" class="btn btn-outline">Назад к списку</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Данные баннера</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.banners._form')

                <div style="display:flex;gap:8px;margin-top:8px">
                    <button type="submit" class="btn btn-primary">Создать</button>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline">Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection

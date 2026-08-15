@extends('layouts.gentelella')

@section('title', 'Новый товар')
@section('page', 'products-create')
@section('breadcrumb', 'Главная > Каталог > Товары > Создание')

@push('scripts')
    @vite(['resources/js/admin-products-form.js'])
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Товары</div>
                <h1 class="page-title">Новый товар</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Назад к списку</a>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header"><h3 class="card-title">Данные товара</h3></div>
            <div class="card-body">
                @include('admin.products._form')
                <div style="display:flex;gap:8px;margin-top:8px">
                    <button type="submit" class="btn btn-primary">Создать</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Отмена</a>
                </div>
            </div>
        </div>
    </form>
@endsection

@extends('layouts.gentelella')

@section('title', 'Редактирование товара')
@section('page', 'products-edit')
@section('breadcrumb', 'Главная > Каталог > Товары > Редактирование')

@push('scripts')
    @vite(['resources/js/admin-products-form.js'])
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Товары</div>
                <h1 class="page-title">{{ $product->name }}</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Назад к списку</a>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header"><h3 class="card-title">Редактирование</h3></div>
            <div class="card-body">
                @include('admin.products._form', ['product' => $product])
                <div style="display:flex;gap:8px;margin-top:8px">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Отмена</a>
                </div>
            </div>
        </div>
    </form>
@endsection

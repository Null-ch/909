@extends('layouts.gentelella')

@section('title', 'Редактирование категории')
@section('page', 'categories-edit')
@section('breadcrumb', 'Главная > Каталог > Категории > Редактирование')

@push('scripts')
    @vite(['resources/js/admin-categories-form.js'])
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Категории</div>
                <h1 class="page-title">{{ $category->name }}</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Назад к списку</a>
            </div>
        </div>
    </div>

    @if ($category->products_count > 0)
        <div class="alert alert-danger" style="margin-bottom: var(--gap);">
            К этой категории привязано товаров: {{ $category->products_count }}.
            При удалении категории товары не удаляются, а только отвязываются.
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Редактирование</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.categories._form', ['category' => $category])

                <div style="display:flex;gap:8px;margin-top:8px">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Отмена</a>
                </div>
            </form>
        </div>
    </div>
@endsection

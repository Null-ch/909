@extends('layouts.gentelella')

@section('title', 'Товары')
@section('page', 'products')
@section('breadcrumb', 'Главная > Каталог > Товары')

@push('scripts')
    @vite(['resources/js/admin-products-index.js'])
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Каталог</div>
                <h1 class="page-title">Товары</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.export.products') }}" class="btn btn-outline">Экспорт в Excel</a>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M8 2v12M2 8h12"/>
                    </svg>
                    Добавить товар
                </a>
            </div>
        </div>
    </div>

    <div class="admin-card-stack">
        <div class="card">
            <div class="card-body">
                <div class="form-row" id="product-filters">
                    <div class="form-group">
                        <label class="form-label" for="filter-category">Категория</label>
                        <select id="filter-category" class="form-control">
                            <option value="">Все категории</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="filter-status">Статус</label>
                        <select id="filter-status" class="form-control">
                            <option value="">Все</option>
                            <option value="active">Активные</option>
                            <option value="inactive">Неактивные</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="filter-featured">Хит продаж</label>
                        <select id="filter-featured" class="form-control">
                            <option value="">Все</option>
                            <option value="hit">Хит</option>
                            <option value="not_hit">Не хит</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="filter-price-min">Цена от</label>
                        <input type="number" id="filter-price-min" class="form-control" min="0" step="0.01" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="filter-price-max">Цена до</label>
                        <input type="number" id="filter-price-max" class="form-control" min="0" step="0.01" placeholder="100000">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Список товаров</h3>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-responsive">
                    <table
                        id="products-table"
                        class="table"
                        data-datatable-server
                        data-ajax-url="{{ route('admin.products.index') }}"
                        style="width:100%"
                    >
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th data-orderable="false">Фото</th>
                                <th>Название</th>
                                <th>Цена</th>
                                <th>Остаток</th>
                                <th>Статус</th>
                                <th data-orderable="false" style="text-align:right">Действия</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

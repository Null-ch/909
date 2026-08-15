@extends('layouts.gentelella')

@section('title', 'Категории')
@section('page', 'categories')
@section('breadcrumb', 'Главная > Каталог > Категории')

@push('scripts')
    @vite(['resources/js/admin-categories-index.js'])
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Каталог</div>
                <h1 class="page-title">Категории</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M8 2v12M2 8h12"/>
                    </svg>
                    Добавить категорию
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Список категорий</h3>
            <span style="color: var(--text-muted); font-size: 14px;">Иерархия до 3 уровней</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table
                    id="categories-table"
                    class="table"
                    data-datatable-server
                    data-ajax-url="{{ route('admin.categories.index') }}"
                    style="width:100%"
                >
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Статус</th>
                            <th data-orderable="false" style="text-align:right">Действия</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

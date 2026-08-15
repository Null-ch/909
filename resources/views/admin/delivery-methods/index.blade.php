@extends('layouts.gentelella')

@section('title', 'Способы доставки')
@section('page', 'delivery-methods')
@section('breadcrumb', 'Главная > Доставка')

@push('scripts')
    @vite(['resources/js/admin-delivery-methods-index.js'])
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Логистика</div>
                <h1 class="page-title">Способы доставки</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.delivery-methods.create') }}" class="btn btn-primary">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M8 2v12M2 8h12"/>
                    </svg>
                    Добавить способ
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Тарифные планы</h3>
            <span style="color: var(--text-muted); font-size: 14px;">Обычная, экспресс и другие варианты с ценами по весу и габаритам</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table
                    id="delivery-methods-table"
                    class="table"
                    data-datatable-server
                    data-ajax-url="{{ route('admin.delivery-methods.index') }}"
                    style="width:100%"
                >
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Тарифов</th>
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

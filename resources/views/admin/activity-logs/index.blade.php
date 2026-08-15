@extends('layouts.gentelella')

@section('title', 'Журнал действий')
@section('page', 'activity-logs')
@section('breadcrumb', 'Главная > Журнал действий')

@push('scripts')
    @vite(['resources/js/admin-activity-logs-index.js'])
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Аудит</div>
                <h1 class="page-title">Журнал действий</h1>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Действия администраторов</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table
                    id="activity-logs-table"
                    class="table"
                    data-datatable-server
                    data-ajax-url="{{ route('admin.activity-logs.index') }}"
                    style="width:100%"
                >
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Администратор</th>
                            <th>Действие</th>
                            <th>Сущность</th>
                            <th>Описание</th>
                            <th>Дата</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

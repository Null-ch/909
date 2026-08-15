@extends('layouts.gentelella')

@section('title', 'Новый способ доставки')
@section('page', 'delivery-methods')
@section('breadcrumb', 'Главная > Доставка > Создание')

@push('scripts')
    @vite(['resources/js/admin-delivery-methods-form.js'])
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Доставка</div>
                <h1 class="page-title">Новый способ доставки</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.delivery-methods.index') }}" class="btn btn-outline">Назад</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.delivery-methods.store') }}">
                @csrf
                @include('admin.delivery-methods._form')
                <div class="form-actions right">
                    <a href="{{ route('admin.delivery-methods.index') }}" class="btn btn-outline">Отмена</a>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
@endsection

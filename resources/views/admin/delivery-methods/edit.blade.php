@extends('layouts.gentelella')

@section('title', 'Редактирование доставки')
@section('page', 'delivery-methods')
@section('breadcrumb', 'Главная > Доставка > '.$deliveryMethod->name)

@push('scripts')
    @vite(['resources/js/admin-delivery-methods-form.js'])
@endpush

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Доставка</div>
                <h1 class="page-title">{{ $deliveryMethod->name }}</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.delivery-methods.index') }}" class="btn btn-outline">Назад</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.delivery-methods.update', $deliveryMethod) }}">
                @csrf
                @method('PUT')
                @include('admin.delivery-methods._form', ['deliveryMethod' => $deliveryMethod])
                <div class="form-actions right">
                    <a href="{{ route('admin.delivery-methods.index') }}" class="btn btn-outline">Отмена</a>
                    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>
@endsection

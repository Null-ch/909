@extends('layouts.gentelella')

@section('title', 'Баннеры')
@section('page', 'banners')
@section('breadcrumb', 'Главная > Главная страница > Баннеры')

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Главная страница</div>
                <h1 class="page-title">Баннеры</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M8 2v12M2 8h12"/>
                    </svg>
                    Добавить баннер
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Слайды на главной странице</h3>
            <span style="color: var(--text-muted); font-size: 14px;">Порядок показа сверху вниз соответствует порядку слайдов</span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:56px">Порядок</th>
                            <th style="width:140px">Изображение</th>
                            <th>Заголовок</th>
                            <th>Статус</th>
                            <th style="text-align:right">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banners as $banner)
                            <tr>
                                <td>
                                    <div style="display:flex;flex-direction:column;gap:4px">
                                        <form method="POST" action="{{ route('admin.banners.move-up', $banner) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline btn-sm" style="padding:2px 8px" @if($loop->first) disabled @endif title="Выше">
                                                <svg viewBox="0 0 16 16" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M8 13V3M3.5 7.5 8 3l4.5 4.5"/>
                                                </svg>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.banners.move-down', $banner) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline btn-sm" style="padding:2px 8px" @if($loop->last) disabled @endif title="Ниже">
                                                <svg viewBox="0 0 16 16" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M8 3v10M3.5 8.5 8 13l4.5-4.5"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                <td>
                                    @if($banner->image)
                                        <img src="{{ asset('storage/'.$banner->image) }}" alt="{{ $banner->title }}" style="width:120px;height:44px;object-fit:cover;border-radius:var(--radius);border:1px solid var(--border-color-light)">
                                    @else
                                        <span style="color: var(--text-muted); font-size: 13px;">Без изображения</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight:600">{{ $banner->title }}</div>
                                    @if($banner->subtitle)
                                        <div style="color: var(--text-muted); font-size: 13px;">{{ \Illuminate\Support\Str::limit($banner->subtitle, 80) }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($banner->is_active)
                                        <span class="badge badge-teal">Активен</span>
                                    @else
                                        <span class="badge badge-red">Неактивен</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex;gap:8px;justify-content:flex-end">
                                        <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-outline btn-sm">Изменить</a>
                                        <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" data-confirm="Удалить баннер «{{ $banner->title }}»?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline btn-sm" style="color:var(--danger)">Удалить</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;color:var(--text-muted);padding:32px">
                                    Баннеров пока нет. Добавьте первый слайд для главной страницы.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

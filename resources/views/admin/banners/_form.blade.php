@php
    $isEdit = isset($banner);
@endphp

<div class="form-row">
    <div class="form-group">
        <label class="form-label" for="title">Заголовок <span class="required">*</span></label>
        <input
            type="text"
            id="title"
            name="title"
            class="form-control @error('title') is-invalid @enderror"
            value="{{ old('title', $banner->title ?? '') }}"
            required
        >
        @error('title')
            <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="sort_order">Порядок сортировки</label>
        <input
            type="number"
            id="sort_order"
            name="sort_order"
            class="form-control @error('sort_order') is-invalid @enderror"
            value="{{ old('sort_order', $banner->sort_order ?? $nextSortOrder ?? 0) }}"
            min="0"
        >
        @error('sort_order')
            <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label" for="subtitle">Подзаголовок</label>
    <textarea
        id="subtitle"
        name="subtitle"
        class="form-control @error('subtitle') is-invalid @enderror"
        rows="2"
    >{{ old('subtitle', $banner->subtitle ?? '') }}</textarea>
    @error('subtitle')
        <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
    @enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label" for="link">Ссылка</label>
        <input
            type="text"
            id="link"
            name="link"
            class="form-control @error('link') is-invalid @enderror"
            value="{{ old('link', $banner->link ?? '') }}"
            placeholder="/catalog или /category/rulonnyy-gazon"
        >
        <div class="form-help">Куда ведёт кнопка баннера. Можно указать относительный путь.</div>
        @error('link')
            <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="button_text">Текст кнопки</label>
        <input
            type="text"
            id="button_text"
            name="button_text"
            class="form-control @error('button_text') is-invalid @enderror"
            value="{{ old('button_text', $banner->button_text ?? '') }}"
            placeholder="Смотреть ассортимент"
        >
        @error('button_text')
            <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label" for="image">Изображение баннера</label>
    <div class="image-dropzone" data-image-dropzone>
        <input
            type="file"
            id="image"
            name="image"
            accept="image/jpeg,image/png,image/webp,image/gif"
        >
        <div class="image-dropzone-preview" data-dropzone-preview>
            @if ($isEdit && $banner->image)
                <img src="{{ asset('storage/'.$banner->image) }}" alt="{{ $banner->title }}">
            @endif
        </div>
        <div class="image-dropzone-empty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path d="M12 16V4M12 4l-4 4M12 4l4 4" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p style="margin:0">Перетащите изображение сюда или</p>
            <button type="button" class="btn btn-outline btn-sm" data-dropzone-browse>Выбрать файл</button>
        </div>
        <button type="button" class="image-dropzone-remove" data-dropzone-remove aria-label="Убрать изображение">×</button>
    </div>
    <div class="form-help">Рекомендуемый размер: 1920×700. Форматы: JPG, PNG, WebP, GIF. Если не загружено — используется градиентная заливка.</div>
    @error('image')
        <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label class="form-check">
        <input
            type="checkbox"
            name="is_active"
            value="1"
            {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}
        >
        Баннер активен
    </label>
</div>

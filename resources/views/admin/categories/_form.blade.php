@php
    $isEdit = isset($category);
@endphp

<div class="form-row">
    <div class="form-group">
        <label class="form-label" for="name">Название <span class="required">*</span></label>
        <input
            type="text"
            id="name"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $category->name ?? '') }}"
            required
            data-slug-source
        >
        @error('name')
            <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="slug">URL (slug)</label>
        <input
            type="text"
            id="slug"
            name="slug"
            class="form-control @error('slug') is-invalid @enderror"
            value="{{ old('slug', $category->slug ?? '') }}"
            placeholder="auto-generated-from-name"
            data-slug-target
        >
        <div class="form-help">Оставьте пустым для автогенерации из названия.</div>
        @error('slug')
            <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label" for="sort_order">Порядок сортировки</label>
    <input
        type="number"
        id="sort_order"
        name="sort_order"
        class="form-control @error('sort_order') is-invalid @enderror"
        value="{{ old('sort_order', $category->sort_order ?? 0) }}"
        min="0"
    >
    @error('sort_order')
        <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label class="form-label" for="description">Описание</label>
    <div class="rich-text" data-rich-text>
        <textarea
            id="description"
            name="description"
            hidden
        >{{ old('description', $isEdit ? $category->editorDescription() : '') }}</textarea>
    </div>
    @error('description')
        <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label class="form-label" for="image">Изображение категории</label>
    <input
        type="file"
        id="image"
        name="image"
        class="form-control @error('image') is-invalid @enderror"
        accept="image/jpeg,image/png,image/webp,image/gif"
        data-image-preview="category-image-preview"
    >
    <div class="form-help">Рекомендуемый размер: 800×600. Форматы: JPG, PNG, WebP, GIF.</div>
    @error('image')
        <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
    @enderror

    <div id="category-image-preview" style="margin-top:12px">
        @if ($isEdit && $category->image)
            <img
                src="{{ asset('storage/'.$category->image) }}"
                alt="{{ $category->name }}"
                style="max-width:320px;border-radius:var(--radius);border:1px solid var(--border-color-light)"
            >
        @endif
    </div>
</div>

<div class="form-group">
    <label class="form-check">
        <input
            type="checkbox"
            name="is_active"
            value="1"
            {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
        >
        Категория активна
    </label>
</div>

<div class="card" style="margin-top: var(--gap);">
    <div class="card-header">
        <div class="card-title">SEO</div>
        <div class="card-subtitle">Мета-теги для страницы категории в каталоге.</div>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label class="form-label" for="meta_title">Meta Title</label>
            <input
                type="text"
                id="meta_title"
                name="meta_title"
                class="form-control @error('meta_title') is-invalid @enderror"
                value="{{ old('meta_title', $category->meta_title ?? '') }}"
            >
            @error('meta_title')
                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="meta_description">Meta Description</label>
            <textarea
                id="meta_description"
                name="meta_description"
                class="form-control @error('meta_description') is-invalid @enderror"
                rows="3"
            >{{ old('meta_description', $category->meta_description ?? '') }}</textarea>
            @error('meta_description')
                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

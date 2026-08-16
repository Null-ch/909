@php
    $isEdit = isset($product);
    $selectedCategories = old('category_ids', $isEdit ? $product->categories->pluck('id')->all() : []);
    $attributes = old('attributes', $isEdit ? $product->attributes->map(fn ($a) => ['name' => $a->attribute_name, 'value' => $a->attribute_value])->all() : [['name' => '', 'value' => '']]);
@endphp

<div class="form-row">
    <div class="form-group">
        <label class="form-label" for="name">Название <span class="required">*</span></label>
        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name ?? '') }}" required data-slug-source>
        @error('name')<div class="form-help" style="color:var(--danger)">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="sku">Артикул (SKU) <span class="required">*</span></label>
        <input type="text" id="sku" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku ?? '') }}" required>
        @error('sku')<div class="form-help" style="color:var(--danger)">{{ $message }}</div>@enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label" for="slug">URL (slug)</label>
        <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $product->slug ?? '') }}" data-slug-target>
        <div class="form-help">Оставьте пустым для автогенерации из названия.</div>
        @error('slug')<div class="form-help" style="color:var(--danger)">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="weight">Вес (кг)</label>
        <input type="number" id="weight" name="weight" class="form-control @error('weight') is-invalid @enderror" value="{{ old('weight', $product->weight ?? '') }}" min="0" step="0.01">
        @error('weight')<div class="form-help" style="color:var(--danger)">{{ $message }}</div>@enderror
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label" for="length">Длина (см)</label>
        <input type="number" id="length" name="length" class="form-control @error('length') is-invalid @enderror" value="{{ old('length', $product->length ?? '') }}" min="0" step="0.01">
        @error('length')<div class="form-help" style="color:var(--danger)">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="width">Ширина (см)</label>
        <input type="number" id="width" name="width" class="form-control @error('width') is-invalid @enderror" value="{{ old('width', $product->width ?? '') }}" min="0" step="0.01">
        @error('width')<div class="form-help" style="color:var(--danger)">{{ $message }}</div>@enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="height">Высота (см)</label>
        <input type="number" id="height" name="height" class="form-control @error('height') is-invalid @enderror" value="{{ old('height', $product->height ?? '') }}" min="0" step="0.01">
        @error('height')<div class="form-help" style="color:var(--danger)">{{ $message }}</div>@enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label" for="short_description">Краткое описание</label>
    <textarea id="short_description" name="short_description" class="form-control @error('short_description') is-invalid @enderror" rows="3">{{ old('short_description', $product->short_description ?? '') }}</textarea>
    @error('short_description')<div class="form-help" style="color:var(--danger)">{{ $message }}</div>@enderror
</div>

<div class="form-group">
    <label class="form-label" for="description">Полное описание</label>
    <div class="rich-text" data-rich-text>
        <textarea id="description" name="description" hidden>{{ old('description', $isEdit ? $product->editorDescription() : '') }}</textarea>
    </div>
    @error('description')<div class="form-help" style="color:var(--danger)">{{ $message }}</div>@enderror
</div>

<div class="card" style="margin-top:var(--gap)">
    <div class="card-header">
        <div class="card-title">Категории</div>
        <div class="card-subtitle">Выберите одну или несколько категорий из списка.</div>
    </div>
    <div class="card-body">
        <div class="form-group" style="margin-bottom:0">
            <label class="form-label" for="category_ids">Категории</label>
            <div class="multi-select" data-multi-select>
                <select id="category_ids" name="category_ids[]" multiple hidden>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(in_array($category->id, $selectedCategories))>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-help">Начните вводить название, чтобы быстро найти категорию.</div>
        </div>
        @error('category_ids')<div class="form-help" style="color:var(--danger)">{{ $message }}</div>@enderror
    </div>
</div>

<div class="card" style="margin-top:var(--gap)">
    <div class="card-header"><div class="card-title">Цена и наличие</div></div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="price">Цена, ₽ <span class="required">*</span></label>
                <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price ?? '0.00') }}" min="0" step="0.01" required>
                @error('price')<div class="form-help" style="color:var(--danger)">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="old_price">Старая цена, ₽</label>
                <input type="number" id="old_price" name="old_price" class="form-control @error('old_price') is-invalid @enderror" value="{{ old('old_price', $product->old_price ?? '') }}" min="0" step="0.01">
                @error('old_price')<div class="form-help" style="color:var(--danger)">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="quantity">Остаток <span class="required">*</span></label>
                <input type="number" id="quantity" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $product->quantity ?? 0) }}" min="0" required>
                @error('quantity')<div class="form-help" style="color:var(--danger)">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-row">
            <label class="form-check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))> Товар активен</label>
            <label class="form-check"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured ?? false))> Хит продаж</label>
        </div>
    </div>
</div>

<div class="card" style="margin-top:var(--gap)">
    <div class="card-header">
        <div class="card-title">Характеристики</div>
        <button type="button" class="btn btn-outline btn-sm" id="add-attribute">+ Добавить</button>
    </div>
    <div class="card-body" id="attributes-list">
        @foreach ($attributes as $index => $attribute)
            <div class="form-row attribute-row" style="margin-bottom:8px">
                <div class="form-group">
                    <input type="text" name="attributes[{{ $index }}][name]" class="form-control" placeholder="Название" value="{{ $attribute['name'] ?? '' }}">
                </div>
                <div class="form-group">
                    <input type="text" name="attributes[{{ $index }}][value]" class="form-control" placeholder="Значение" value="{{ $attribute['value'] ?? '' }}">
                </div>
                <div class="form-group" style="flex:0 0 auto">
                    <button type="button" class="btn btn-outline btn-sm remove-attribute">×</button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="card" style="margin-top:var(--gap)">
    <div class="card-header"><div class="card-title">Галерея изображений</div></div>
    <div class="card-body">
        @if ($isEdit && $product->images->isNotEmpty())
            <div id="existing-gallery" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;margin-bottom:16px">
                @foreach ($product->images as $image)
                    <div class="gallery-item card" data-image-id="{{ $image->id }}" draggable="true" style="padding:8px">
                        <img src="{{ asset('storage/'.$image->image_path) }}" alt="" style="width:100%;height:100px;object-fit:cover;border-radius:6px">
                        <label class="form-check" style="margin-top:8px;font-size:12px">
                            <input type="radio" name="main_image_id" value="{{ $image->id }}" @checked($image->is_main)> Главное
                        </label>
                        <label class="form-check" style="font-size:12px">
                            <input type="checkbox" name="delete_image_ids[]" value="{{ $image->id }}"> Удалить
                        </label>
                        <input type="hidden" name="image_order[]" value="{{ $image->id }}">
                    </div>
                @endforeach
            </div>
        @endif

        <div id="gallery-dropzone" class="image-dropzone image-dropzone-multi">
            <input type="file" id="images" name="images[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple>
            <div class="image-dropzone-empty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <path d="M12 16V4M12 4l-4 4M12 4l4 4" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <p style="margin:0">Перетащите изображения сюда или</p>
                <button type="button" class="btn btn-outline btn-sm" id="gallery-browse-btn">Выбрать файлы</button>
            </div>
        </div>
        <div id="new-gallery-preview" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;margin-top:16px"></div>
        <div id="new-main-image-picker" style="margin-top:12px;display:none">
            <div class="form-help">Главное среди новых изображений:</div>
            <div id="new-main-image-options"></div>
        </div>
        @error('images')<div class="form-help" style="color:var(--danger)">{{ $message }}</div>@enderror
        @error('images.*')<div class="form-help" style="color:var(--danger)">{{ $message }}</div>@enderror
    </div>
</div>

<div class="card" style="margin-top:var(--gap)">
    <div class="card-header"><div class="card-title">SEO</div></div>
    <div class="card-body">
        <div class="form-group">
            <label class="form-label" for="meta_title">Meta Title</label>
            <input type="text" id="meta_title" name="meta_title" class="form-control" value="{{ old('meta_title', $product->meta_title ?? '') }}">
        </div>
        <div class="form-group">
            <label class="form-label" for="meta_description">Meta Description</label>
            <textarea id="meta_description" name="meta_description" class="form-control" rows="3">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
        </div>
    </div>
</div>

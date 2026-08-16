@php
    $isEdit = isset($deliveryMethod);
    $rates = old('rates', $isEdit
        ? $deliveryMethod->rates->map(fn ($rate) => [
            'name' => $rate->name,
            'min_weight' => $rate->min_weight,
            'max_weight' => $rate->max_weight,
            'min_volume' => $rate->min_volume,
            'max_volume' => $rate->max_volume,
            'max_length' => $rate->max_length,
            'max_width' => $rate->max_width,
            'max_height' => $rate->max_height,
            'price' => $rate->price,
            'is_active' => $rate->is_active,
        ])->all()
        : [[
            'name' => '',
            'min_weight' => 0,
            'max_weight' => '',
            'min_volume' => 0,
            'max_volume' => '',
            'max_length' => '',
            'max_width' => '',
            'max_height' => '',
            'price' => '',
            'is_active' => true,
        ]]);
@endphp

<div class="form-row">
    <div class="form-group">
        <label class="form-label" for="name">Название <span class="required">*</span></label>
        <input
            type="text"
            id="name"
            name="name"
            class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $deliveryMethod->name ?? '') }}"
            required
            data-slug-source
            placeholder="Например: Экспресс-доставка"
        >
        @error('name')
            <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-label" for="slug">Код (slug)</label>
        <input
            type="text"
            id="slug"
            name="slug"
            class="form-control @error('slug') is-invalid @enderror"
            value="{{ old('slug', $deliveryMethod->slug ?? '') }}"
            data-slug-target
            placeholder="express"
        >
        <div class="form-help">Оставьте пустым для автогенерации.</div>
        @error('slug')
            <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-label" for="description">Описание</label>
    <textarea
        id="description"
        name="description"
        class="form-control @error('description') is-invalid @enderror"
        rows="2"
    >{{ old('description', $deliveryMethod->description ?? '') }}</textarea>
    @error('description')
        <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
    @enderror
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label" for="sort_order">Порядок сортировки</label>
        <input
            type="number"
            id="sort_order"
            name="sort_order"
            class="form-control @error('sort_order') is-invalid @enderror"
            value="{{ old('sort_order', $deliveryMethod->sort_order ?? 0) }}"
            min="0"
        >
        @error('sort_order')
            <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group" style="display:flex;align-items:flex-end">
        <label class="form-check">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $deliveryMethod->is_active ?? true) ? 'checked' : '' }}>
            Способ доставки активен
        </label>
    </div>
</div>

<div class="card" style="margin-top: var(--admin-gap, 24px);">
    <div class="card-header">
        <div>
            <div class="card-title">Тарифные диапазоны</div>
            <div class="card-subtitle">Цена зависит от суммарного веса (кг), объёма (м³) и максимальных габаритов одной позиции (см).</div>
        </div>
        <button type="button" class="btn btn-outline btn-sm" data-add-rate-row>+ Добавить тариф</button>
    </div>
    <div class="card-body" id="delivery-rates" data-rates-container>
        @foreach ($rates as $index => $rate)
            <div class="delivery-rate-row" data-rate-row>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Название тарифа</label>
                        <input type="text" name="rates[{{ $index }}][name]" class="form-control" value="{{ $rate['name'] ?? '' }}" placeholder="До 5 кг">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Цена, ₽ <span class="required">*</span></label>
                        <input type="number" name="rates[{{ $index }}][price]" class="form-control" value="{{ $rate['price'] ?? '' }}" min="0" step="0.01" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Вес от, кг</label>
                        <input type="number" name="rates[{{ $index }}][min_weight]" class="form-control" value="{{ $rate['min_weight'] ?? 0 }}" min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Вес до, кг</label>
                        <input type="number" name="rates[{{ $index }}][max_weight]" class="form-control" value="{{ $rate['max_weight'] ?? '' }}" min="0" step="0.01" placeholder="∞">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Объём от, м³</label>
                        <input type="number" name="rates[{{ $index }}][min_volume]" class="form-control" value="{{ $rate['min_volume'] ?? 0 }}" min="0" step="0.0001">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Объём до, м³</label>
                        <input type="number" name="rates[{{ $index }}][max_volume]" class="form-control" value="{{ $rate['max_volume'] ?? '' }}" min="0" step="0.0001" placeholder="∞">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Макс. длина, см</label>
                        <input type="number" name="rates[{{ $index }}][max_length]" class="form-control" value="{{ $rate['max_length'] ?? '' }}" min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Макс. ширина, см</label>
                        <input type="number" name="rates[{{ $index }}][max_width]" class="form-control" value="{{ $rate['max_width'] ?? '' }}" min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Макс. высота, см</label>
                        <input type="number" name="rates[{{ $index }}][max_height]" class="form-control" value="{{ $rate['max_height'] ?? '' }}" min="0" step="0.01">
                    </div>
                    <div class="form-group" style="display:flex;align-items:flex-end;justify-content:space-between;gap:12px">
                        <label class="form-check">
                            <input type="checkbox" name="rates[{{ $index }}][is_active]" value="1" {{ ($rate['is_active'] ?? true) ? 'checked' : '' }}>
                            Активен
                        </label>
                        <button type="button" class="btn btn-outline btn-sm" data-remove-rate-row style="color:var(--danger)">Удалить</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<template id="delivery-rate-template">
    <div class="delivery-rate-row" data-rate-row>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Название тарифа</label>
                <input type="text" data-field="name" class="form-control" placeholder="До 5 кг">
            </div>
            <div class="form-group">
                <label class="form-label">Цена, ₽ <span class="required">*</span></label>
                <input type="number" data-field="price" class="form-control" min="0" step="0.01" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Вес от, кг</label>
                <input type="number" data-field="min_weight" class="form-control" value="0" min="0" step="0.01">
            </div>
            <div class="form-group">
                <label class="form-label">Вес до, кг</label>
                <input type="number" data-field="max_weight" class="form-control" min="0" step="0.01" placeholder="∞">
            </div>
            <div class="form-group">
                <label class="form-label">Объём от, м³</label>
                <input type="number" data-field="min_volume" class="form-control" value="0" min="0" step="0.0001">
            </div>
            <div class="form-group">
                <label class="form-label">Объём до, м³</label>
                <input type="number" data-field="max_volume" class="form-control" min="0" step="0.0001" placeholder="∞">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Макс. длина, см</label>
                <input type="number" data-field="max_length" class="form-control" min="0" step="0.01">
            </div>
            <div class="form-group">
                <label class="form-label">Макс. ширина, см</label>
                <input type="number" data-field="max_width" class="form-control" min="0" step="0.01">
            </div>
            <div class="form-group">
                <label class="form-label">Макс. высота, см</label>
                <input type="number" data-field="max_height" class="form-control" min="0" step="0.01">
            </div>
            <div class="form-group" style="display:flex;align-items:flex-end;justify-content:space-between;gap:12px">
                <label class="form-check">
                    <input type="checkbox" data-field="is_active" value="1" checked>
                    Активен
                </label>
                <button type="button" class="btn btn-outline btn-sm" data-remove-rate-row style="color:var(--danger)">Удалить</button>
            </div>
        </div>
    </div>
</template>

<div class="catalog-page py-4">
    <div class="container">
        @include('front.partials.breadcrumbs', ['items' => $breadcrumbs])

        <div class="catalog-page__header d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
            <div>
                <h1 class="catalog-page__title h2 mb-2">
                    {{ $category?->name ?? 'Каталог товаров' }}
                </h1>
                @if($category?->plainDescription())
                    <p class="catalog-page__description text-muted mb-0">{{ $category->plainDescription() }}</p>
                @endif
            </div>

            <div class="catalog-page__sort">
                <label for="catalog-sort" class="form-label small text-muted mb-1">Сортировка</label>
                <select id="catalog-sort" wire:model.live="sort" class="form-select">
                    <option value="newest">Сначала новые</option>
                    <option value="price_asc">Цена: по возрастанию</option>
                    <option value="price_desc">Цена: по убыванию</option>
                    <option value="name">По названию</option>
                </select>
            </div>
        </div>

        <div class="row g-4">
            <aside class="col-lg-3">
                <div class="catalog-filters card border-0 shadow-sm"
                     x-data="{ mobileOpen: false }">
                    <div class="card-header bg-white d-lg-none">
                        <button type="button"
                                class="btn btn-outline-primary w-100"
                                @click="mobileOpen = !mobileOpen">
                            <i class="fa-solid fa-filter me-2"></i>Фильтры
                        </button>
                    </div>

                    <div class="card-body d-none d-lg-block" :class="{ 'd-block': mobileOpen }">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h2 class="h6 mb-0">Фильтры</h2>
                            <button type="button" wire:click="resetFilters" class="btn btn-link btn-sm p-0">
                                Сбросить
                            </button>
                        </div>

                        <div class="catalog-filters__section mb-4">
                            <label class="form-label fw-semibold">Поиск</label>
                            <input type="search"
                                   wire:model.live.debounce.400ms="search"
                                   class="form-control"
                                   placeholder="Название, артикул...">
                        </div>

                        <div class="catalog-filters__section mb-4">
                            <label class="form-label fw-semibold">Цена, ₽</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number"
                                           wire:model.live.debounce.500ms="priceMin"
                                           min="{{ $priceBoundMin }}"
                                           max="{{ $priceBoundMax }}"
                                           class="form-control form-control-sm"
                                           placeholder="От">
                                </div>
                                <div class="col-6">
                                    <input type="number"
                                           wire:model.live.debounce.500ms="priceMax"
                                           min="{{ $priceBoundMin }}"
                                           max="{{ $priceBoundMax }}"
                                           class="form-control form-control-sm"
                                           placeholder="До">
                                </div>
                            </div>
                            <div class="form-text">
                                {{ number_format($priceBoundMin, 0, ',', ' ') }} — {{ number_format($priceBoundMax, 0, ',', ' ') }} ₽
                            </div>
                        </div>

                        <div class="catalog-filters__section">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="form-label fw-semibold mb-0">Категории</label>
                            </div>

                            <div class="catalog-filters__categories">
                                @foreach($filterTree as $rootCategory)
                                    <div class="catalog-filters__group" x-data="{ open: true }">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            @if($rootCategory->children->isNotEmpty())
                                                <button type="button"
                                                        class="btn btn-sm btn-link text-secondary p-0"
                                                        @click="open = !open">
                                                    <i class="fa-solid" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                                                </button>
                                            @else
                                                <span class="catalog-filters__spacer"></span>
                                            @endif
                                            <label class="form-check mb-0 flex-grow-1">
                                                <input type="checkbox"
                                                       class="form-check-input"
                                                       wire:model.live="selectedCategories"
                                                       value="{{ (string) $rootCategory->id }}">
                                                <span class="form-check-label">{{ $rootCategory->name }}</span>
                                            </label>
                                        </div>

                                        @if($rootCategory->children->isNotEmpty())
                                            <div class="catalog-filters__children" x-show="open" x-transition>
                                                @foreach($rootCategory->children as $childCategory)
                                                    <label class="form-check mb-1 ps-4">
                                                        <input type="checkbox"
                                                               class="form-check-input"
                                                               wire:model.live="selectedCategories"
                                                               value="{{ (string) $childCategory->id }}">
                                                        <span class="form-check-label">{{ $childCategory->name }}</span>
                                                    </label>

                                                    @foreach($childCategory->children as $grandchild)
                                                        <label class="form-check mb-1 ps-5">
                                                            <input type="checkbox"
                                                                   class="form-check-input"
                                                                   wire:model.live="selectedCategories"
                                                                   value="{{ (string) $grandchild->id }}">
                                                            <span class="form-check-label">{{ $grandchild->name }}</span>
                                                        </label>
                                                    @endforeach
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="col-lg-9">
                <div class="catalog-page__meta text-muted small mb-3">
                    Найдено товаров: <strong>{{ $products->total() }}</strong>
                </div>

                @if($products->isEmpty())
                    <div class="alert alert-light border text-center py-5">
                        <i class="fa-solid fa-box-open fa-2x text-muted mb-3"></i>
                        <p class="mb-3">По вашему запросу товары не найдены.</p>
                        <button type="button" wire:click="resetFilters" class="btn btn-primary">
                            Сбросить фильтры
                        </button>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach($products as $product)
                            <div class="col-xl-4 col-md-6" wire:key="catalog-product-{{ $product->id }}">
                                <livewire:product-card :product="$product" :key="'catalog-card-'.$product->id" />
                            </div>
                        @endforeach
                    </div>

                    <div class="catalog-page__pagination mt-4">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

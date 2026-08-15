<?php

namespace App\Livewire;

use App\Models\Category;
use App\Services\CatalogService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Catalog extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public ?string $categorySlug = null;

    public ?Category $category = null;

    public int $perPage = 12;

    #[Url]
    public string $sort = 'newest';

    #[Url]
    public float $priceMin = 0;

    #[Url]
    public float $priceMax = 0;

    /** @var array<int, string> */
    #[Url]
    public array $selectedCategories = [];

    #[Url]
    public string $search = '';

    public float $priceBoundMin = 0;

    public float $priceBoundMax = 100000;

    public function mount(?string $slug = null, ?CatalogService $catalogService = null): void
    {
        $catalogService ??= app(CatalogService::class);
        $this->bootstrapPriceBounds($catalogService);

        if ($slug) {
            $this->categorySlug = $slug;
            $this->category = Category::query()
                ->with('parent')
                ->where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail();

            if ($this->selectedCategories === []) {
                $this->selectedCategories = $catalogService
                    ->getCategoryAndDescendantIds($this->category)
                    ->map(fn ($id) => (string) $id)
                    ->all();
            }
        }
    }

    public function updated($property): void
    {
        if (! in_array($property, ['categorySlug', 'category', 'priceBoundMin', 'priceBoundMax', 'priceMin', 'priceMax'], true)) {
            $this->resetPage();
        }
    }

    public function updatedSelectedCategories(): void
    {
        $this->selectedCategories = array_values(array_map(
            static fn ($id) => (string) $id,
            $this->selectedCategories,
        ));
        $this->resetPage();
    }

    public function updatedPriceMin(): void
    {
        $this->priceMin = max($this->priceBoundMin, min($this->priceMin, $this->priceMax));
        $this->resetPage();
    }

    public function updatedPriceMax(): void
    {
        $this->priceMax = max($this->priceMin, min($this->priceMax, $this->priceBoundMax));
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->sort = 'newest';
        $this->search = '';
        $this->priceMin = $this->priceBoundMin;
        $this->priceMax = $this->priceBoundMax;
        $this->selectedCategories = $this->category
            ? app(CatalogService::class)->getCategoryAndDescendantIds($this->category)->map(fn ($id) => (string) $id)->all()
            : [];
        $this->resetPage();
    }

    public function render(CatalogService $catalogService)
    {
        $categoryIds = collect($this->selectedCategories)
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        $products = $catalogService->getProducts(
            categoryIds: $categoryIds,
            search: trim($this->search),
            priceMin: $this->priceMin,
            priceMax: $this->priceMax,
            priceBoundMin: $this->priceBoundMin,
            priceBoundMax: $this->priceBoundMax,
            sort: $this->sort,
            perPage: $this->perPage,
        );

        return view('livewire.catalog', [
            'products' => $products,
            'filterTree' => $catalogService->getFilterTree(),
            'pageTitle' => $this->pageTitle(),
            'pageDescription' => $this->pageDescription(),
            'breadcrumbs' => $this->breadcrumbs(),
        ])->layout('layouts.app', [
            'metaTitle' => $this->pageTitle(),
            'metaDescription' => $this->pageDescription(),
        ]);
    }

    protected function bootstrapPriceBounds(CatalogService $catalogService): void
    {
        $bounds = $catalogService->getPriceBounds();
        $this->priceBoundMin = $bounds['min'];
        $this->priceBoundMax = $bounds['max'];

        if ($this->priceMax <= 0) {
            $this->priceMin = $this->priceBoundMin;
            $this->priceMax = $this->priceBoundMax;
        }
    }

    protected function pageTitle(): string
    {
        if ($this->category?->meta_title) {
            return $this->category->meta_title;
        }

        if ($this->category) {
            return $this->category->name.' — каталог';
        }

        return 'Каталог товаров — '.setting('shop_name', config('app.name'));
    }

    protected function pageDescription(): string
    {
        if ($this->category?->meta_description) {
            return $this->category->meta_description;
        }

        return (string) setting('seo_meta_description', '');
    }

    /**
     * @return array<int, array{title: string, url: string}>
     */
    protected function breadcrumbs(): array
    {
        if (! $this->category) {
            return [
                ['title' => 'Каталог', 'url' => url('/catalog')],
            ];
        }

        $items = [
            ['title' => 'Каталог', 'url' => url('/catalog')],
        ];

        if ($this->category->parent) {
            $items[] = [
                'title' => $this->category->parent->name,
                'url' => url('/category/'.$this->category->parent->slug),
            ];
        }

        $items[] = [
            'title' => $this->category->name,
            'url' => url('/category/'.$this->category->slug),
        ];

        return $items;
    }
}

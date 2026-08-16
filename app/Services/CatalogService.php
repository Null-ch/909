<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CatalogService
{
    private const PRICE_BOUNDS_KEY = 'catalog.price_bounds';

    public function __construct(
        private readonly SearchService $searchService,
    ) {}

    /**
     * @return array{min: float, max: float}
     */
    public function getPriceBounds(): array
    {
        return Cache::remember(self::PRICE_BOUNDS_KEY, 3600, function () {
            $bounds = Product::query()
                ->where('is_active', true)
                ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
                ->first();

            $min = (float) ($bounds?->min_price ?? 0);
            $max = (float) ($bounds?->max_price ?? 0);

            if ($max <= $min) {
                $max = $min + 1;
            }

            return [
                'min' => floor($min),
                'max' => ceil($max),
            ];
        });
    }

    /**
     * @return Collection<int, Category>
     */
    public function getFilterTree(): Collection
    {
        return Category::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children' => fn ($query) => $query
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->with(['children' => fn ($childQuery) => $childQuery
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name'),
                ]),
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    /**
     * @param  array<int, int|string>  $categoryIds
     */
    public function getProducts(
        array $categoryIds = [],
        string $search = '',
        float $priceMin = 0,
        float $priceMax = 0,
        float $priceBoundMin = 0,
        float $priceBoundMax = 0,
        string $sort = 'newest',
        int $perPage = 12,
    ): LengthAwarePaginator {
        // The price slider reports its bounds as priceMin/priceMax even when
        // the user hasn't touched it, so only pass a bound through as an
        // actual filter once it's been moved off the slider's own floor/ceiling.
        $effectiveMin = ($priceBoundMax > $priceBoundMin && $priceMin > $priceBoundMin) ? $priceMin : null;
        $effectiveMax = ($priceBoundMax > $priceBoundMin && $priceMax > 0 && $priceMax < $priceBoundMax) ? $priceMax : null;

        return $this->searchService->search(
            categoryIds: $categoryIds,
            search: $search,
            priceMin: $effectiveMin,
            priceMax: $effectiveMax,
            sort: $sort,
            perPage: $perPage,
            page: Paginator::resolveCurrentPage('page'),
        );
    }

    /**
     * @return Collection<int, int>
     */
    public function getCategoryAndDescendantIds(Category $category): Collection
    {
        $ids = collect([$category->id]);
        $parentIds = [$category->id];

        while ($parentIds !== []) {
            $children = Category::query()
                ->whereIn('parent_id', $parentIds)
                ->where('is_active', true)
                ->pluck('id');

            $ids = $ids->merge($children);
            $parentIds = $children->all();
        }

        return $ids->unique()->values();
    }

    public function clearCache(): void
    {
        Cache::forget(self::PRICE_BOUNDS_KEY);
    }
}

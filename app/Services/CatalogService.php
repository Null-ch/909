<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CatalogService
{
    private const PRICE_BOUNDS_KEY = 'catalog.price_bounds';

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
        $query = Product::query()
            ->where('is_active', true)
            ->with(['images' => fn ($imageQuery) => $imageQuery->orderBy('sort_order')]);

        if ($categoryIds !== []) {
            $query->whereHas('categories', fn ($categoryQuery) => $categoryQuery->whereIn('categories.id', $categoryIds));
        }

        if ($search !== '') {
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($priceBoundMax > $priceBoundMin && $priceMin > $priceBoundMin) {
            $query->where('price', '>=', $priceMin);
        }

        if ($priceBoundMax > $priceBoundMin && $priceMax > 0 && $priceMax < $priceBoundMax) {
            $query->where('price', '<=', $priceMax);
        }

        match ($sort) {
            'price_asc' => $query->orderBy('price')->orderBy('name'),
            'price_desc' => $query->orderByDesc('price')->orderBy('name'),
            'name' => $query->orderBy('name'),
            default => $query->orderByDesc('id'),
        };

        return $query->paginate($perPage);
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

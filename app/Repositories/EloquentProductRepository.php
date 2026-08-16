<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class EloquentProductRepository implements ProductRepositoryInterface
{
    /**
     * @param  array<int, int>  $categoryIds
     */
    public function paginateCatalog(
        array $categoryIds,
        string $search,
        ?float $priceMin,
        ?float $priceMax,
        string $sort,
        int $perPage,
        int $page,
    ): LengthAwarePaginator {
        $search = trim($search);

        $query = Product::query()
            ->active()
            ->inCategories($categoryIds)
            ->search($search)
            ->priceBetween($priceMin, $priceMax)
            ->with(['images' => fn ($imageQuery) => $imageQuery->orderBy('sort_order')]);

        $usesRelevance = $search !== ''
            && $sort === 'newest'
            && in_array($query->getModel()->getConnection()->getDriverName(), ['mysql', 'mariadb'], true)
            && mb_strlen($search) >= 3;

        if ($usesRelevance) {
            $query->selectRaw(
                'products.*, MATCH(name, short_description, description) AGAINST (? IN NATURAL LANGUAGE MODE) as relevance',
                [$search]
            )->orderByDesc('relevance');
        }

        $this->applySort($query, $sort);

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * When relevance ordering was already applied above, this adds `id` only
     * as a stable tiebreaker for otherwise-equal relevance scores.
     */
    private function applySort(Builder $query, string $sort): void
    {
        match ($sort) {
            'price_asc' => $query->orderBy('price')->orderBy('name'),
            'price_desc' => $query->orderByDesc('price')->orderBy('name'),
            'name' => $query->orderBy('name'),
            default => $query->orderByDesc('id'),
        };
    }
}

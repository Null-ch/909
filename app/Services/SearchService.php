<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;

/**
 * Orchestrates product catalog/search queries: sanitizes the raw search
 * term, normalizes filters and caches the resulting page for a short TTL.
 *
 * Only the matching product IDs and total count are cached — never
 * hydrated Eloquent models/paginators. The `database` cache store (per
 * config/cache.php) round-trips values through PHP's serialize(), and a
 * cached LengthAwarePaginator full of Product/ProductImage models reliably
 * comes back as __PHP_Incomplete_Class on the next request, breaking the
 * catalog page. Caching plain ints and rehydrating with a cheap
 * whereIn('id', ...) sidesteps that entirely and, as a bonus, always shows
 * current price/stock/images even when the ID list itself is a cache hit.
 *
 * The cache uses the default store's TTL-based expiry rather than tags,
 * because this app's cache store does not support tagging. A short TTL was
 * chosen over manual invalidation so that product create/update/delete
 * (ProductService) don't need to know about every possible filter
 * combination that might be cached.
 */
class SearchService
{
    private const MAX_TERM_LENGTH = 100;

    private const CACHE_TTL_SECONDS = 120;

    public function __construct(
        private readonly ProductRepositoryInterface $products,
    ) {}

    /**
     * @param  array<int, int|string>  $categoryIds
     */
    public function search(
        array $categoryIds,
        string $search,
        ?float $priceMin,
        ?float $priceMax,
        string $sort,
        int $perPage,
        int $page,
    ): LengthAwarePaginatorContract {
        $categoryIds = collect($categoryIds)
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->sort()
            ->values()
            ->all();

        $search = $this->sanitizeTerm($search);
        $sort = $this->sanitizeSort($sort);
        $perPage = max(1, min(60, $perPage));
        $page = max(1, $page);

        $cacheKey = $this->cacheKey($categoryIds, $search, $priceMin, $priceMax, $sort, $perPage, $page);

        /** @var array{ids: array<int, int>, total: int} $matched */
        $matched = Cache::remember($cacheKey, self::CACHE_TTL_SECONDS, function () use (
            $categoryIds, $search, $priceMin, $priceMax, $sort, $perPage, $page
        ) {
            $paginator = $this->products->paginateCatalog(
                $categoryIds,
                $search,
                $priceMin,
                $priceMax,
                $sort,
                $perPage,
                $page,
            );

            return [
                'ids' => $paginator->getCollection()->pluck('id')->all(),
                'total' => $paginator->total(),
            ];
        });

        return $this->hydrate($matched['ids'], $matched['total'], $perPage, $page);
    }

    /**
     * Rehydrates a page of Product models, in the cached ID order, without
     * ever caching the models themselves.
     *
     * @param  array<int, int>  $ids
     */
    private function hydrate(array $ids, int $total, int $perPage, int $page): LengthAwarePaginatorContract
    {
        $products = Product::query()
            ->whereIn('id', $ids)
            ->with(['images' => fn ($imageQuery) => $imageQuery->orderBy('sort_order')])
            ->get()
            ->sortBy(fn (Product $product) => array_search($product->id, $ids, true))
            ->values();

        return new LengthAwarePaginator($products, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    /**
     * Strips tags and control characters, and caps the length of a raw
     * search term before it ever reaches a query.
     */
    public function sanitizeTerm(string $term): string
    {
        $term = strip_tags($term);
        $term = preg_replace('/[\x00-\x1F\x7F]/u', '', $term) ?? '';
        $term = trim(preg_replace('/\s+/u', ' ', $term) ?? '');

        return mb_substr($term, 0, self::MAX_TERM_LENGTH);
    }

    private function sanitizeSort(string $sort): string
    {
        return in_array($sort, ['newest', 'price_asc', 'price_desc', 'name'], true) ? $sort : 'newest';
    }

    /**
     * @param  array<int, int>  $categoryIds
     */
    private function cacheKey(
        array $categoryIds,
        string $search,
        ?float $priceMin,
        ?float $priceMax,
        string $sort,
        int $perPage,
        int $page,
    ): string {
        return 'search.products.'.md5(json_encode([
            'categories' => $categoryIds,
            'q' => $search,
            'min' => $priceMin,
            'max' => $priceMax,
            'sort' => $sort,
            'per_page' => $perPage,
            'page' => $page,
        ]));
    }
}

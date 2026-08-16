<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
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
    ): LengthAwarePaginator;
}

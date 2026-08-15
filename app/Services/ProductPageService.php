<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductPageService
{
    public function findBySlug(string $slug): Product
    {
        return Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'images' => fn ($query) => $query->orderBy('sort_order'),
                'attributes',
                'categories.parent' => fn ($query) => $query->orderBy('sort_order'),
                'categories' => fn ($query) => $query->orderBy('sort_order'),
            ])
            ->firstOrFail();
    }

    /**
     * @return Collection<int, Product>
     */
    public function getRelatedProducts(Product $product, int $limit = 4): Collection
    {
        $categoryIds = $product->categories->pluck('id');

        return Product::query()
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->when(
                $categoryIds->isNotEmpty(),
                fn ($query) => $query->whereHas(
                    'categories',
                    fn ($categoryQuery) => $categoryQuery->whereIn('categories.id', $categoryIds),
                ),
            )
            ->with(['images' => fn ($query) => $query->orderBy('sort_order')])
            ->orderByDesc('is_featured')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    /**
     * @return array<int, array{title: string, url: string}>
     */
    public function breadcrumbs(Product $product): array
    {
        $items = [
            ['title' => 'Каталог', 'url' => url('/catalog')],
        ];

        $category = $product->categories->first();

        if ($category?->parent) {
            $items[] = [
                'title' => $category->parent->name,
                'url' => url('/category/'.$category->parent->slug),
            ];
        }

        if ($category) {
            $items[] = [
                'title' => $category->name,
                'url' => url('/category/'.$category->slug),
            ];
        }

        $items[] = [
            'title' => $product->name,
            'url' => url('/product/'.$product->slug),
        ];

        return $items;
    }
}

<?php

namespace Tests\Feature;

use App\Livewire\Catalog;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class CatalogSearchTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(string $name, string $sku, float $price = 100): Product
    {
        return Product::query()->create([
            'name' => $name,
            'slug' => Str::slug($name).'-'.$sku,
            'sku' => $sku,
            'price' => $price,
            'quantity' => 5,
            'is_active' => true,
        ]);
    }

    /**
     * Nested `<livewire:product-card>` components render as empty
     * placeholder divs inside Livewire::test()'s snapshot (there's no JS
     * runtime in the test harness to hydrate them), so product names can't
     * be asserted via assertSee() here — that path is covered end-to-end
     * instead by SearchTest, which drives a real HTTP request/response.
     * Here we assert directly against the paginator the component builds.
     */
    public function test_search_matches_by_name(): void
    {
        $this->createProduct('Электропила Makita', 'SAW-001');
        $this->createProduct('Шланг поливочный', 'HOSE-001');

        $products = Livewire::test(Catalog::class)
            ->set('search', 'Электропила')
            ->viewData('products');

        $this->assertSame(['Электропила Makita'], $products->pluck('name')->all());
    }

    public function test_search_matches_by_sku(): void
    {
        $this->createProduct('Насос садовый', 'PUMP-777');

        $products = Livewire::test(Catalog::class)
            ->set('search', 'PUMP-777')
            ->viewData('products');

        $this->assertSame(['Насос садовый'], $products->pluck('name')->all());
    }

    public function test_inactive_products_never_appear_in_search(): void
    {
        $product = $this->createProduct('Секатор садовый', 'CUT-001');
        $product->update(['is_active' => false]);

        Livewire::test(Catalog::class)
            ->set('search', 'Секатор')
            ->assertSee('По вашему запросу товары не найдены.');
    }

    public function test_search_results_are_paginated(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            $this->createProduct("Тестовый товар {$i}", "TEST-{$i}");
        }

        $component = Livewire::test(Catalog::class)->set('search', 'Тестовый товар');

        $products = $component->viewData('products');

        $this->assertSame(15, $products->total());
        $this->assertCount(12, $products->items());
    }

    public function test_sorting_by_price_ascending_orders_results(): void
    {
        $this->createProduct('Товар дешёвый', 'SORT-1', 50);
        $this->createProduct('Товар дорогой', 'SORT-2', 500);

        $products = Livewire::test(Catalog::class)
            ->set('search', 'Товар')
            ->set('sort', 'price_asc')
            ->viewData('products');

        $this->assertSame(['Товар дешёвый', 'Товар дорогой'], $products->pluck('name')->all());
    }

    public function test_underscore_metacharacter_in_the_query_is_treated_literally(): void
    {
        $this->createProduct('Товар A1C классический', 'MISC-001');

        // "_" is a single-character LIKE wildcard. Without escaping it,
        // searching for "A_C" would match "A1C" (any char in place of
        // "_"); with escaping, "A_C" only matches that literal substring,
        // which this product's name/sku don't contain.
        Livewire::test(Catalog::class)
            ->set('search', 'A_C')
            ->assertSee('По вашему запросу товары не найдены.');
    }
}

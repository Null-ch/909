<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    private function createProduct(string $name, string $sku, ?string $description = null): Product
    {
        return Product::query()->create([
            'name' => $name,
            'slug' => Str::slug($name).'-'.$sku,
            'sku' => $sku,
            'description' => $description,
            'short_description' => null,
            'price' => 100,
            'quantity' => 5,
            'is_active' => true,
        ]);
    }

    public function test_search_route_exists_and_is_rate_limited(): void
    {
        $route = Route::getRoutes()->getByName('search');

        $this->assertNotNull($route, 'The /search route referenced by the header search form must exist.');
        $this->assertContains('throttle:60,1', $route->middleware());
    }

    public function test_search_redirects_into_the_catalog_with_the_term_prefilled(): void
    {
        $response = $this->get('/search?q=велосипед');

        $response->assertRedirect();
        $this->assertStringContainsString('search=', $response->headers->get('Location'));
    }

    public function test_empty_search_redirects_to_the_plain_catalog(): void
    {
        $response = $this->get('/search?q=');

        $response->assertRedirect(route('catalog'));
    }

    public function test_search_term_longer_than_100_characters_is_rejected(): void
    {
        $response = $this->get('/search?q='.str_repeat('a', 150));

        $response->assertSessionHasErrors('q');
    }

    public function test_search_finds_a_matching_product_by_name(): void
    {
        $this->createProduct('Горный велосипед Stels', 'BIKE-001');
        $this->createProduct('Газонокосилка электрическая', 'MOW-001');

        $response = $this->followingRedirects()->get('/search?q=велосипед');

        $response->assertOk();
        $response->assertSee('Горный велосипед Stels');
        $response->assertDontSee('Газонокосилка электрическая');
    }

    public function test_sql_injection_payload_is_treated_as_a_literal_string(): void
    {
        $this->createProduct('Триммер садовый', 'TRIM-001');

        $response = $this->followingRedirects()->get('/search?'.http_build_query(['q' => "' OR 1=1 --"]));

        $response->assertOk();
        // A real injection would leak every row; a safely-parameterized
        // search finds nothing for this literal string and shows the
        // catalog's own empty state instead of dumping the whole catalog.
        $response->assertSee('По вашему запросу товары не найдены.');
        $response->assertDontSee('Триммер садовый');
    }

    public function test_xss_payload_is_neutralized_in_the_rendered_page(): void
    {
        $payload = '<script>alert(1)</script>';

        $response = $this->followingRedirects()->get('/search?'.http_build_query(['q' => $payload]));

        $response->assertOk();
        $response->assertDontSee($payload, false);
    }
}

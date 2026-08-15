<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\ProductPageService;
use Livewire\Component;

class RelatedProducts extends Component
{
    public Product $product;

    public function mount(Product $product): void
    {
        $this->product = $product;
    }

    public function render(ProductPageService $productPageService)
    {
        $products = $productPageService->getRelatedProducts($this->product);

        return view('livewire.related-products', [
            'products' => $products,
        ]);
    }
}

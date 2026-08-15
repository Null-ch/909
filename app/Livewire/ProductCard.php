<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\CartService;
use Livewire\Component;

class ProductCard extends Component
{
    public Product $product;

    public function addToCart(CartService $cartService): void
    {
        $cartService->addItem($this->product, 1);
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.product-card');
    }
}

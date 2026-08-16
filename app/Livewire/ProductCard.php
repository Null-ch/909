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
        $this->dispatch('cart-item-added', message: 'Товар добавлен в корзину');
    }

    public function render()
    {
        return view('livewire.product-card');
    }
}

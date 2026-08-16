<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\CartService;
use Livewire\Component;

class AddToCart extends Component
{
    public Product $product;

    public int $quantity = 1;

    public function mount(Product $product): void
    {
        $this->product = $product;
    }

    public function increment(): void
    {
        if ($this->quantity < max(1, (int) $this->product->quantity)) {
            $this->quantity++;
        }
    }

    public function decrement(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart(CartService $cartService): void
    {
        $cartService->addItem($this->product, $this->quantity);
        $this->dispatch('cart-updated');
        $this->dispatch('cart-item-added', message: 'Товар добавлен в корзину');
    }

    public function render()
    {
        return view('livewire.add-to-cart');
    }
}

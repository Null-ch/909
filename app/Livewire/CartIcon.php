<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartIcon extends Component
{
    public int $itemsCount = 0;

    public float $totalPrice = 0;

    public function mount(CartService $cartService): void
    {
        $this->updateCart($cartService);
    }

    #[On('cart-updated')]
    public function updateCart(?CartService $cartService = null): void
    {
        $cartService ??= app(CartService::class);

        $this->itemsCount = $cartService->getItemsCount();
        $this->totalPrice = $cartService->getTotal();
    }

    public function render()
    {
        return view('livewire.cart-icon');
    }
}

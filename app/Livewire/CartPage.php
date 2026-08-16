<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;

class CartPage extends Component
{
    public function incrementItem(int $cartItemId, CartService $cartService): void
    {
        $item = $cartService->getCurrentItems()->firstWhere('id', $cartItemId);

        if ($item) {
            $cartService->updateQuantity($cartItemId, $item->quantity + 1);
        }

        $this->dispatch('cart-updated');
    }

    public function decrementItem(int $cartItemId, CartService $cartService): void
    {
        $item = $cartService->getCurrentItems()->firstWhere('id', $cartItemId);

        if ($item && $item->quantity > 1) {
            $cartService->updateQuantity($cartItemId, $item->quantity - 1);
        }

        $this->dispatch('cart-updated');
    }

    public function updateQuantity(int $cartItemId, $quantity, CartService $cartService): void
    {
        $cartService->updateQuantity($cartItemId, (int) $quantity);
        $this->dispatch('cart-updated');
    }

    public function removeItem(int $cartItemId, CartService $cartService): void
    {
        $cartService->removeItem($cartItemId);
        $this->dispatch('cart-updated');
    }

    public function render(CartService $cartService)
    {
        $items = $cartService->getCurrentItems();

        return view('livewire.cart-page', [
            'items' => $items,
            'total' => (float) $items->sum(fn ($item) => $item->lineTotal()),
        ])->layout('layouts.app', [
            'metaTitle' => 'Корзина — '.setting('shop_name'),
        ]);
    }
}

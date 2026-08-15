<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * @return Collection<int, Cart>
     */
    public function getCurrentItems(): Collection
    {
        return Cart::query()
            ->with('product')
            ->when(
                Auth::check(),
                fn ($query) => $query->where('user_id', Auth::id()),
                fn ($query) => $query->where('session_id', session()->getId()),
            )
            ->get();
    }

    public function getItemsCount(): int
    {
        return (int) $this->getCurrentItems()->sum('quantity');
    }

    public function getTotal(): float
    {
        return (float) $this->getCurrentItems()->sum(fn (Cart $cart) => $cart->lineTotal());
    }

    public function addItem(Product $product, int $quantity = 1): void
    {
        $quantity = max(1, $quantity);
        $maxQuantity = max(1, (int) $product->quantity);
        $quantity = min($quantity, $maxQuantity);

        $cartItem = Cart::query()
            ->when(
                Auth::check(),
                fn ($query) => $query->where('user_id', Auth::id()),
                fn ($query) => $query->where('session_id', session()->getId()),
            )
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $newQuantity = min($cartItem->quantity + $quantity, $maxQuantity);
            $cartItem->update([
                'quantity' => $newQuantity,
                'price' => $product->price,
            ]);

            return;
        }

        Cart::query()->create([
            'user_id' => Auth::id(),
            'session_id' => Auth::check() ? null : session()->getId(),
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->price,
        ]);
    }

    /**
     * @return Collection<int, array{key: string, buyer: string, products: string, total: string, updated_at: string}>
     */
    public function activeCarts(): Collection
    {
        $since = Carbon::now()->subHours(6);

        $items = Cart::query()
            ->with(['user', 'product'])
            ->where('updated_at', '>=', $since)
            ->orderByDesc('updated_at')
            ->get();

        return $items
            ->groupBy(fn (Cart $cart) => $cart->user_id ? 'user:'.$cart->user_id : 'session:'.$cart->session_id)
            ->map(function (Collection $group) {
                /** @var Cart $first */
                $first = $group->first();

                $buyer = $first->user
                    ? $first->user->name.' ('.$first->user->email.')'
                    : 'Гость: '.($first->session_id ?? '—');

                $products = $group->map(function (Cart $cart) {
                    $name = $cart->product?->name ?? 'Товар #'.$cart->product_id;

                    return $name.' × '.$cart->quantity;
                })->implode(', ');

                $total = $group->sum(fn (Cart $cart) => $cart->lineTotal());

                return [
                    'key' => $first->user_id ? 'user:'.$first->user_id : 'session:'.$first->session_id,
                    'buyer' => $buyer,
                    'products' => $products,
                    'total' => number_format($total, 2, '.', ' ').' ₽',
                    'updated_at' => $group->max('updated_at')?->format('d.m.Y H:i') ?? '—',
                ];
            })
            ->values();
    }
}

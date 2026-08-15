<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::query()->limit(2)->get();
        $user = User::query()->where('email', 'test@example.com')->first();

        if ($products->isEmpty()) {
            return;
        }

        Cart::query()->create([
            'user_id' => $user?->id,
            'session_id' => null,
            'product_id' => $products[0]->id,
            'quantity' => 2,
            'price' => $products[0]->price,
        ]);

        if (isset($products[1])) {
            Cart::query()->create([
                'user_id' => null,
                'session_id' => Str::uuid()->toString(),
                'product_id' => $products[1]->id,
                'quantity' => 1,
                'price' => $products[1]->price,
            ]);
        }
    }
}

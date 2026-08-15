<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $rollCategory = Category::query()->where('slug', 'roll-gazon')->first();
        $mixCategory = Category::query()->where('slug', 'travy-mix')->first();

        $product = Product::query()->create([
            'name' => 'Рулонный газон «Премиум»',
            'slug' => 'roll-gazon-premium',
            'sku' => 'GAZ-ROLL-001',
            'short_description' => 'Готовый рулонный газон премиум-класса для частных участков.',
            'description' => '<p>Плотный рулонный газон с высокой устойчивостью к нагрузкам. Идеален для дач и загородных домов.</p>',
            'price' => 450.00,
            'old_price' => 520.00,
            'quantity' => 120,
            'is_active' => true,
            'is_featured' => true,
            'weight' => 12.5,
            'meta_title' => 'Рулонный газон Премиум — купить',
            'meta_description' => 'Рулонный газон премиум-класса с доставкой.',
        ]);

        if ($rollCategory) {
            $product->categories()->attach($rollCategory->id);
        }

        $product->attributes()->createMany([
            ['attribute_name' => 'Высота травы', 'attribute_value' => '3-4 см'],
            ['attribute_name' => 'Устойчивость к морозам', 'attribute_value' => 'до -25°C'],
            ['attribute_name' => 'Площадь рулона', 'attribute_value' => '1 м²'],
        ]);

        $mixProduct = Product::query()->create([
            'name' => 'Смесь трав «Универсал»',
            'slug' => 'travy-universal',
            'sku' => 'SEED-MIX-001',
            'short_description' => 'Универсальная смесь семян для посева газона.',
            'description' => '<p>Сбалансированная травосмесь для солнечных и полутенистых участков.</p>',
            'price' => 890.00,
            'quantity' => 300,
            'is_active' => true,
            'is_featured' => false,
            'weight' => 2.0,
        ]);

        if ($mixCategory) {
            $mixProduct->categories()->attach($mixCategory->id);
        }
    }
}

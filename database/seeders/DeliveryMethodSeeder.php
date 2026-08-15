<?php

namespace Database\Seeders;

use App\Models\DeliveryMethod;
use Illuminate\Database\Seeder;

class DeliveryMethodSeeder extends Seeder
{
    public function run(): void
    {
        $standard = DeliveryMethod::query()->firstOrCreate(
            ['slug' => 'standard'],
            [
                'name' => 'Обычная доставка',
                'description' => 'Стандартная доставка по городу и области.',
                'is_active' => true,
                'sort_order' => 1,
            ],
        );

        $express = DeliveryMethod::query()->firstOrCreate(
            ['slug' => 'express'],
            [
                'name' => 'Экспресс-доставка',
                'description' => 'Доставка в день заказа или на следующий день.',
                'is_active' => true,
                'sort_order' => 2,
            ],
        );

        $standard->rates()->delete();
        $express->rates()->delete();

        $standard->rates()->createMany([
            [
                'name' => 'До 5 кг',
                'min_weight' => 0,
                'max_weight' => 5,
                'min_volume' => 0,
                'max_volume' => 0.1,
                'max_length' => 100,
                'max_width' => 80,
                'max_height' => 60,
                'price' => 500,
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'name' => '5–20 кг',
                'min_weight' => 5,
                'max_weight' => 20,
                'min_volume' => 0,
                'max_volume' => 0.5,
                'max_length' => 200,
                'max_width' => 120,
                'max_height' => 100,
                'price' => 1200,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'От 20 кг',
                'min_weight' => 20,
                'max_weight' => null,
                'min_volume' => 0,
                'max_volume' => null,
                'max_length' => 300,
                'max_width' => 200,
                'max_height' => 150,
                'price' => 2500,
                'is_active' => true,
                'sort_order' => 2,
            ],
        ]);

        $express->rates()->createMany([
            [
                'name' => 'До 5 кг',
                'min_weight' => 0,
                'max_weight' => 5,
                'min_volume' => 0,
                'max_volume' => 0.1,
                'max_length' => 100,
                'max_width' => 80,
                'max_height' => 60,
                'price' => 1200,
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'name' => '5–15 кг',
                'min_weight' => 5,
                'max_weight' => 15,
                'min_volume' => 0,
                'max_volume' => 0.3,
                'max_length' => 150,
                'max_width' => 100,
                'max_height' => 80,
                'price' => 2200,
                'is_active' => true,
                'sort_order' => 1,
            ],
        ]);
    }
}

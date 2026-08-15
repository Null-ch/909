<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $roll = Category::query()->create([
            'name' => 'Рулонный газон',
            'slug' => 'roll-gazon',
            'description' => 'Готовые рулоны газона для быстрого озеленения.',
            'is_active' => true,
            'sort_order' => 1,
            'meta_title' => 'Рулонный газон — купить',
            'meta_description' => 'Каталог рулонного газона для ландшафтного дизайна.',
        ]);

        Category::query()->create([
            'parent_id' => $roll->id,
            'name' => 'Спортивные газоны',
            'slug' => 'sport-gazon',
            'description' => 'Износостойкие покрытия для спортивных площадок.',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Category::query()->create([
            'parent_id' => $roll->id,
            'name' => 'Декоративные газоны',
            'slug' => 'decor-gazon',
            'description' => 'Газоны для частных участков и парков.',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Category::query()->create([
            'name' => 'Смеси трав',
            'slug' => 'travy-mix',
            'description' => 'Семена и смеси для посева газона.',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Category::query()->create([
            'name' => 'Удобрения',
            'slug' => 'udobreniya',
            'description' => 'Уход за газоном и подкормка.',
            'is_active' => true,
            'sort_order' => 3,
        ]);
    }
}

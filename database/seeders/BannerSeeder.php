<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Красивый газон за один день',
                'subtitle' => 'Рулонный газон от производителя с доставкой по региону',
                'link' => '/catalog',
                'button_text' => 'Смотреть ассортимент',
                'sort_order' => 1,
            ],
            [
                'title' => 'Семена и смеси трав',
                'subtitle' => 'Широкий выбор газонных смесей для любого участка',
                'link' => '/category/travy-mix',
                'button_text' => 'Выбрать смесь',
                'sort_order' => 2,
            ],
            [
                'title' => 'Удобрения для газона',
                'subtitle' => 'Профессиональный уход и подкормка круглый год',
                'link' => '/category/udobreniya',
                'button_text' => 'Перейти в каталог',
                'sort_order' => 3,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::query()->create([
                ...$banner,
                'is_active' => true,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Adds 3 products to each category seeded by CategorySeeder.
     */
    public function run(): void
    {
        $productsByCategory = [
            'roll-gazon' => [
                [
                    'name' => 'Рулонный газон «Стандарт»',
                    'slug' => 'roll-gazon-standart',
                    'sku' => 'GAZ-ROLL-101',
                    'short_description' => 'Доступный рулонный газон для повседневного озеленения.',
                    'description' => '<p>Практичное решение для оформления придомовой территории без переплаты за премиум-класс.</p>',
                    'price' => 320.00,
                    'quantity' => 200,
                    'is_featured' => false,
                    'weight' => 12.0,
                    'length' => 200,
                    'width' => 100,
                    'height' => 5,
                ],
                [
                    'name' => 'Рулонный газон «Люкс»',
                    'slug' => 'roll-gazon-lyuks',
                    'sku' => 'GAZ-ROLL-102',
                    'short_description' => 'Плотный рулонный газон высшего сорта с ровным травостоем.',
                    'description' => '<p>Отборный дёрн с плотной корневой системой, укладывается без видимых швов.</p>',
                    'price' => 590.00,
                    'old_price' => 650.00,
                    'quantity' => 90,
                    'is_featured' => true,
                    'weight' => 13.0,
                    'length' => 200,
                    'width' => 100,
                    'height' => 5,
                ],
                [
                    'name' => 'Рулонный газон «Элит»',
                    'slug' => 'roll-gazon-elit',
                    'sku' => 'GAZ-ROLL-103',
                    'short_description' => 'Элитный рулонный газон для ландшафтных проектов.',
                    'description' => '<p>Используется в парковых зонах и на объектах с высокими требованиями к внешнему виду.</p>',
                    'price' => 740.00,
                    'quantity' => 60,
                    'is_featured' => false,
                    'weight' => 13.5,
                    'length' => 200,
                    'width' => 100,
                    'height' => 5,
                ],
            ],
            'sport-gazon' => [
                [
                    'name' => 'Спортивный газон «Профи»',
                    'slug' => 'sport-gazon-profi',
                    'sku' => 'GAZ-SPORT-101',
                    'short_description' => 'Износостойкий газон для футбольных и игровых полей.',
                    'description' => '<p>Выдерживает интенсивные нагрузки и быстро восстанавливается после игр.</p>',
                    'price' => 680.00,
                    'quantity' => 150,
                    'is_featured' => true,
                    'weight' => 14.0,
                    'length' => 200,
                    'width' => 100,
                    'height' => 6,
                ],
                [
                    'name' => 'Спортивный газон «Стадион»',
                    'slug' => 'sport-gazon-stadion',
                    'sku' => 'GAZ-SPORT-102',
                    'short_description' => 'Профессиональное покрытие для стадионов и спортивных комплексов.',
                    'description' => '<p>Усиленная дернина с глубокой корневой системой для стабильного покрытия.</p>',
                    'price' => 820.00,
                    'quantity' => 80,
                    'is_featured' => false,
                    'weight' => 14.5,
                    'length' => 200,
                    'width' => 100,
                    'height' => 6,
                ],
                [
                    'name' => 'Спортивный газон «Тренировочный»',
                    'slug' => 'sport-gazon-trenirovochnyy',
                    'sku' => 'GAZ-SPORT-103',
                    'short_description' => 'Бюджетное покрытие для тренировочных площадок.',
                    'description' => '<p>Хорошее соотношение цены и износостойкости для регулярных тренировок.</p>',
                    'price' => 540.00,
                    'quantity' => 130,
                    'is_featured' => false,
                    'weight' => 13.5,
                    'length' => 200,
                    'width' => 100,
                    'height' => 6,
                ],
            ],
            'decor-gazon' => [
                [
                    'name' => 'Декоративный газон «Партер»',
                    'slug' => 'decor-gazon-parter',
                    'sku' => 'GAZ-DECOR-101',
                    'short_description' => 'Изумрудный газон для парадной части участка.',
                    'description' => '<p>Однородный низкорослый травостой насыщенного зелёного цвета.</p>',
                    'price' => 710.00,
                    'quantity' => 70,
                    'is_featured' => true,
                    'weight' => 12.5,
                    'length' => 200,
                    'width' => 100,
                    'height' => 5,
                ],
                [
                    'name' => 'Декоративный газон «Мавританский»',
                    'slug' => 'decor-gazon-mavritanskiy',
                    'sku' => 'GAZ-DECOR-102',
                    'short_description' => 'Цветущий газон из разнотравья и полевых цветов.',
                    'description' => '<p>Создаёт эффект естественного луга, не требует частой стрижки.</p>',
                    'price' => 480.00,
                    'quantity' => 110,
                    'is_featured' => false,
                    'weight' => 11.0,
                    'length' => 200,
                    'width' => 100,
                    'height' => 5,
                ],
                [
                    'name' => 'Декоративный газон «Классик»',
                    'slug' => 'decor-gazon-klassik',
                    'sku' => 'GAZ-DECOR-103',
                    'short_description' => 'Универсальный декоративный газон для сада и двора.',
                    'description' => '<p>Плотный ровный ковёр, подходит для большинства типов почв.</p>',
                    'price' => 420.00,
                    'quantity' => 140,
                    'is_featured' => false,
                    'weight' => 12.0,
                    'length' => 200,
                    'width' => 100,
                    'height' => 5,
                ],
            ],
            'travy-mix' => [
                [
                    'name' => 'Смесь трав «Спортивная»',
                    'slug' => 'travy-sportivnaya',
                    'sku' => 'SEED-MIX-101',
                    'short_description' => 'Семена для посева износостойкого спортивного газона.',
                    'description' => '<p>Быстро восстанавливается после механических нагрузок и вытаптывания.</p>',
                    'price' => 950.00,
                    'quantity' => 220,
                    'is_featured' => false,
                    'weight' => 9.0,
                    'length' => 30,
                    'width' => 20,
                    'height' => 15,
                ],
                [
                    'name' => 'Смесь трав «Теневыносливая»',
                    'slug' => 'travy-tenevynoslivaya',
                    'sku' => 'SEED-MIX-102',
                    'short_description' => 'Семена для посева газона в тенистых участках сада.',
                    'description' => '<p>Хорошо прорастает под кронами деревьев и у построек с недостатком солнца.</p>',
                    'price' => 870.00,
                    'quantity' => 180,
                    'is_featured' => false,
                    'weight' => 8.0,
                    'length' => 30,
                    'width' => 20,
                    'height' => 15,
                ],
                [
                    'name' => 'Смесь трав «Быстрый рост»',
                    'slug' => 'travy-bystryy-rost',
                    'sku' => 'SEED-MIX-103',
                    'short_description' => 'Семена ускоренного прорастания для быстрого озеленения.',
                    'description' => '<p>Первые всходы уже через 5-7 дней после посева.</p>',
                    'price' => 830.00,
                    'quantity' => 240,
                    'is_featured' => true,
                    'weight' => 8.0,
                    'length' => 30,
                    'width' => 20,
                    'height' => 15,
                ],
            ],
            'udobreniya' => [
                [
                    'name' => 'Удобрение «Весеннее»',
                    'slug' => 'udobrenie-vesennee',
                    'sku' => 'FERT-101',
                    'short_description' => 'Азотное удобрение для активного роста газона весной.',
                    'description' => '<p>Стимулирует наращивание зелёной массы после зимнего периода.</p>',
                    'price' => 350.00,
                    'quantity' => 300,
                    'is_featured' => false,
                    'weight' => 5.0,
                    'length' => 25,
                    'width' => 18,
                    'height' => 8,
                ],
                [
                    'name' => 'Удобрение «Летнее»',
                    'slug' => 'udobrenie-letnee',
                    'sku' => 'FERT-102',
                    'short_description' => 'Комплексное удобрение для ухода за газоном в летний сезон.',
                    'description' => '<p>Поддерживает насыщенный цвет и устойчивость к засухе.</p>',
                    'price' => 380.00,
                    'quantity' => 260,
                    'is_featured' => false,
                    'weight' => 5.0,
                    'length' => 25,
                    'width' => 18,
                    'height' => 8,
                ],
                [
                    'name' => 'Удобрение «Осеннее»',
                    'slug' => 'udobrenie-osennee',
                    'sku' => 'FERT-103',
                    'short_description' => 'Калийно-фосфорное удобрение для подготовки газона к зиме.',
                    'description' => '<p>Укрепляет корневую систему и повышает морозостойкость травы.</p>',
                    'price' => 400.00,
                    'quantity' => 250,
                    'is_featured' => false,
                    'weight' => 5.0,
                    'length' => 25,
                    'width' => 18,
                    'height' => 8,
                ],
            ],
        ];

        foreach ($productsByCategory as $categorySlug => $products) {
            $category = Category::query()->where('slug', $categorySlug)->first();

            foreach ($products as $data) {
                $data['is_active'] = true;

                $product = Product::query()->create($data);

                if ($category) {
                    $product->categories()->attach($category->id);
                }
            }
        }
    }
}

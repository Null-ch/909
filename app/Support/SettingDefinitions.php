<?php

namespace App\Support;

class SettingDefinitions
{
    /**
     * @return array<string, array{group: string, type: string, label: string, default: string|null}>
     */
    public static function all(): array
    {
        return [
            'shop_name' => [
                'group' => 'general',
                'type' => 'text',
                'label' => 'Название магазина',
                'default' => 'ГазонМаркет',
            ],
            'shop_description' => [
                'group' => 'general',
                'type' => 'textarea',
                'label' => 'Описание магазина',
                'default' => 'Интернет-магазин по продаже газонов и сопутствующих товаров.',
            ],
            'about_text' => [
                'group' => 'general',
                'type' => 'wysiwyg',
                'label' => 'О компании',
                'default' => '<p>Мы специализируемся на продаже качественного газона для частных и коммерческих объектов.</p>',
            ],
            'footer_text' => [
                'group' => 'general',
                'type' => 'textarea',
                'label' => 'Текст в футере',
                'default' => '© ГазонМаркет. Все права защищены.',
            ],
            'logo' => [
                'group' => 'logo',
                'type' => 'image',
                'label' => 'Логотип',
                'default' => null,
            ],
            'favicon' => [
                'group' => 'logo',
                'type' => 'image',
                'label' => 'Favicon',
                'default' => null,
            ],
            'social_vk' => [
                'group' => 'social',
                'type' => 'text',
                'label' => 'ВКонтакте',
                'default' => null,
            ],
            'social_telegram' => [
                'group' => 'social',
                'type' => 'text',
                'label' => 'Telegram',
                'default' => null,
            ],
            'social_whatsapp' => [
                'group' => 'social',
                'type' => 'text',
                'label' => 'WhatsApp',
                'default' => null,
            ],
            'benefits' => [
                'group' => 'benefits',
                'type' => 'json',
                'label' => 'Карусель преимуществ',
                'default' => json_encode([
                    ['icon' => 'truck', 'title' => 'Доставка', 'text' => 'Быстрая доставка по региону'],
                    ['icon' => 'shield', 'title' => 'Гарантия', 'text' => 'Качество подтверждено сертификатами'],
                    ['icon' => 'leaf', 'title' => 'Экологичность', 'text' => 'Безопасно для детей и животных'],
                    ['icon' => 'wallet', 'title' => 'Доступные цены', 'text' => 'Лучшее соотношение цены и качества'],
                ], JSON_UNESCAPED_UNICODE),
            ],
            'contact_phone' => [
                'group' => 'contacts',
                'type' => 'text',
                'label' => 'Телефон',
                'default' => '+7 (999) 000-00-00',
            ],
            'contact_email' => [
                'group' => 'contacts',
                'type' => 'text',
                'label' => 'Email',
                'default' => 'info@example.com',
            ],
            'contact_address' => [
                'group' => 'contacts',
                'type' => 'textarea',
                'label' => 'Адрес',
                'default' => 'г. Москва, ул. Примерная, д. 1',
            ],
            'contact_map_iframe' => [
                'group' => 'contacts',
                'type' => 'textarea',
                'label' => 'Карта (iframe)',
                'default' => null,
            ],
            'seo_home_title' => [
                'group' => 'home',
                'type' => 'text',
                'label' => 'Meta Title главной',
                'default' => 'ГазонМаркет — купить газон с доставкой',
            ],
            'seo_home_description' => [
                'group' => 'home',
                'type' => 'textarea',
                'label' => 'Meta Description главной',
                'default' => 'Интернет-магазин рулонного газона, семян и удобрений. Доставка по региону, гарантия качества.',
            ],
            'seo_home_text' => [
                'group' => 'home',
                'type' => 'wysiwyg',
                'label' => 'SEO-текст на главной',
                'default' => '<p>«ГазонМаркет» — ваш надёжный поставщик рулонного газона, семян и средств ухода. Мы работаем напрямую с производителями и предлагаем конкурентные цены как для частных клиентов, так и для ландшафтных компаний.</p>',
            ],
            'seo_meta_title' => [
                'group' => 'seo',
                'type' => 'text',
                'label' => 'Глобальный Meta Title',
                'default' => 'ГазонМаркет — интернет-магазин газона',
            ],
            'seo_meta_keywords' => [
                'group' => 'seo',
                'type' => 'textarea',
                'label' => 'Ключевые слова',
                'default' => 'газон, рулонный газон, искусственный газон',
            ],
            'seo_meta_description' => [
                'group' => 'seo',
                'type' => 'textarea',
                'label' => 'Глобальный Meta Description',
                'default' => 'Купить газон с доставкой. Широкий ассортимент, выгодные цены, профессиональная консультация.',
            ],
            'legal_name' => [
                'group' => 'legal',
                'type' => 'text',
                'label' => 'Полное наименование организации',
                'default' => null,
            ],
            'legal_inn' => [
                'group' => 'legal',
                'type' => 'text',
                'label' => 'ИНН',
                'default' => null,
            ],
            'legal_ogrn' => [
                'group' => 'legal',
                'type' => 'text',
                'label' => 'ОГРН / ОГРНИП',
                'default' => null,
            ],
            'legal_kpp' => [
                'group' => 'legal',
                'type' => 'text',
                'label' => 'КПП',
                'default' => null,
            ],
            'legal_address' => [
                'group' => 'legal',
                'type' => 'textarea',
                'label' => 'Юридический адрес',
                'default' => null,
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function groupLabels(): array
    {
        return [
            'general' => 'Общие',
            'logo' => 'Логотип и Favicon',
            'social' => 'Социальные сети',
            'benefits' => 'Карусель преимуществ',
            'contacts' => 'Контакты',
            'home' => 'Главная страница',
            'seo' => 'SEO',
            'legal' => 'Юридическая информация',
        ];
    }
}

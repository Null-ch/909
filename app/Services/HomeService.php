<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class HomeService
{
    private const BANNER_IDS_KEY = 'home.banner_ids';

    private const CATEGORY_IDS_KEY = 'home.category_ids';

    private const FEATURED_IDS_KEY = 'home.featured_ids';

    private const BENEFITS_KEY = 'home.benefits';

    private const SEO_TEXT_KEY = 'home.seo_text';

    public function __construct(
        private readonly SettingService $settingService,
    ) {}

    /**
     * @return array{
     *     banners: Collection<int, Banner>,
     *     benefits: array<int, array{icon?: string, title?: string, text?: string}>,
     *     categories: Collection<int, Category>,
     *     featuredProducts: Collection<int, Product>,
     *     seoText: string|null
     * }
     */
    public function getPageData(): array
    {
        $bannerIds = Cache::remember(self::BANNER_IDS_KEY, 3600, fn () => Banner::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('id')
            ->all());

        $categoryIds = Cache::remember(self::CATEGORY_IDS_KEY, 3600, fn () => Category::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(6)
            ->pluck('id')
            ->all());

        $featuredIds = Cache::remember(self::FEATURED_IDS_KEY, 3600, fn () => Product::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderByDesc('id')
            ->limit(8)
            ->pluck('id')
            ->all());

        $benefits = Cache::remember(self::BENEFITS_KEY, 3600, function () {
            $value = $this->settingService->get('benefits', []);

            return is_array($value) ? $value : [];
        });

        $seoText = Cache::remember(self::SEO_TEXT_KEY, 3600, fn () => $this->settingService->get('seo_home_text'));

        return [
            'banners' => Banner::query()
                ->whereIn('id', $bannerIds)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
            'benefits' => $benefits,
            'categories' => Category::query()
                ->whereIn('id', $categoryIds)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'featuredProducts' => Product::query()
                ->whereIn('id', $featuredIds)
                ->with(['images' => fn ($query) => $query->orderBy('sort_order')])
                ->orderByDesc('id')
                ->get(),
            'seoText' => $seoText,
        ];
    }

    public function clearCache(): void
    {
        foreach ([
            self::BANNER_IDS_KEY,
            self::CATEGORY_IDS_KEY,
            self::FEATURED_IDS_KEY,
            self::BENEFITS_KEY,
            self::SEO_TEXT_KEY,
        ] as $key) {
            Cache::forget($key);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\HomeService;
use App\Services\ProductPageService;
use App\Services\SettingService;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct(
        private readonly HomeService $homeService,
        private readonly SettingService $settingService,
        private readonly ProductPageService $productPageService,
    ) {}

    public function home(): View
    {
        $pageData = $this->homeService->getPageData();

        return view('front.home', [
            ...$pageData,
            'metaTitle' => $this->settingService->get('seo_home_title'),
            'metaDescription' => $this->settingService->get('seo_home_description'),
        ]);
    }

    public function product(string $slug): View
    {
        $product = $this->productPageService->findBySlug($slug);

        return view('front.product', [
            'product' => $product,
            'breadcrumbs' => $this->productPageService->breadcrumbs($product),
            'metaTitle' => $product->meta_title ?: $product->name.' — '.setting('shop_name'),
            'metaDescription' => $product->meta_description ?: $product->short_description,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\HomeService;
use App\Services\ProductPageService;
use App\Services\SettingService;
use Illuminate\Http\Request;
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

    public function about(): View
    {
        return view('front.about', [
            'metaTitle' => 'О компании — '.setting('shop_name'),
            'metaDescription' => $this->settingService->get('seo_meta_description'),
        ]);
    }

    public function contacts(): View
    {
        return view('front.contacts', [
            'metaTitle' => 'Контакты — '.setting('shop_name'),
            'metaDescription' => $this->settingService->get('seo_meta_description'),
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

    public function orderSuccess(Request $request, string $orderNumber): View
    {
        $order = Order::query()
            ->with(['items', 'deliveryMethod'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('front.order-success', [
            'order' => $order,
            'accountStatus' => $request->query('account'),
            'metaTitle' => 'Заказ оформлен — '.setting('shop_name'),
        ]);
    }
}

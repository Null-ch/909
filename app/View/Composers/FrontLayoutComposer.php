<?php

namespace App\View\Composers;

use App\Services\CategoryService;
use App\Services\SettingService;
use Illuminate\View\View;

class FrontLayoutComposer
{
    public function __construct(
        private readonly SettingService $settingService,
        private readonly CategoryService $categoryService,
    ) {}

    public function compose(View $view): void
    {
        $view->with([
            'shopName' => $this->settingService->get('shop_name', config('app.name')),
            'shopDescription' => $this->settingService->get('shop_description'),
            'footerText' => $this->settingService->get('footer_text'),
            'logoUrl' => storage_url($this->settingService->get('logo')),
            'faviconUrl' => storage_url($this->settingService->get('favicon')),
            'contactPhone' => $this->settingService->get('contact_phone'),
            'contactEmail' => $this->settingService->get('contact_email'),
            'contactAddress' => $this->settingService->get('contact_address'),
            'socialVk' => $this->settingService->get('social_vk'),
            'socialTelegram' => $this->settingService->get('social_telegram'),
            'socialWhatsapp' => $this->settingService->get('social_whatsapp'),
            'defaultMetaTitle' => $this->settingService->get('seo_meta_title'),
            'defaultMetaDescription' => $this->settingService->get('seo_meta_description'),
            'defaultMetaKeywords' => $this->settingService->get('seo_meta_keywords'),
            'legalName' => $this->settingService->get('legal_name'),
            'legalInn' => $this->settingService->get('legal_inn'),
            'legalOgrn' => $this->settingService->get('legal_ogrn'),
            'legalKpp' => $this->settingService->get('legal_kpp'),
            'legalAddress' => $this->settingService->get('legal_address'),
            'navCategories' => $this->categoryService->getNavigationTree(),
        ]);
    }
}

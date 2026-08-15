<?php

use App\Services\ActivityLogService;
use App\Services\DeliveryCalculatorService;
use App\Services\SettingService;

if (! function_exists('getSetting')) {
    function getSetting(string $key, mixed $default = null): mixed
    {
        return app(SettingService::class)->get($key, $default);
    }
}

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return getSetting($key, $default);
    }
}

if (! function_exists('storage_url')) {
    function storage_url(?string $path): ?string
    {
        return $path ? asset('storage/'.$path) : null;
    }
}

if (! function_exists('benefit_icon')) {
    function benefit_icon(?string $icon): string
    {
        return match ($icon) {
            'truck' => 'fa-truck',
            'shield' => 'fa-shield-halved',
            'leaf' => 'fa-leaf',
            'tags' => 'fa-tags',
            'store' => 'fa-store',
            'certificate' => 'fa-certificate',
            'phone' => 'fa-phone',
            'star' => 'fa-star',
            default => 'fa-circle-check',
        };
    }
}

if (! function_exists('logActivity')) {
    function logActivity(
        string $action,
        string $entityType,
        ?int $entityId,
        string $description,
        ?array $properties = null,
    ): void {
        app(ActivityLogService::class)->log($action, $entityType, $entityId, $description, $properties);
    }
}

if (! function_exists('calculateDeliveryOptions')) {
    /**
     * @param  iterable<int, array{product: \App\Models\Product, quantity: int}>  $items
     */
    function calculateDeliveryOptions(iterable $items): \Illuminate\Support\Collection
    {
        return app(DeliveryCalculatorService::class)->calculateForItems($items);
    }
}

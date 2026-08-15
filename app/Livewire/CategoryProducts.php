<?php

namespace App\Livewire;

use App\Services\CatalogService;

class CategoryProducts extends Catalog
{
    public function mount(?string $slug = null, ?CatalogService $catalogService = null): void
    {
        abort_unless($slug, 404);

        parent::mount($slug, $catalogService);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name',
    'slug',
    'sku',
    'description',
    'short_description',
    'price',
    'old_price',
    'quantity',
    'is_active',
    'is_featured',
    'weight',
    'length',
    'width',
    'height',
    'meta_title',
    'meta_description',
])]
class Product extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'old_price' => 'decimal:2',
            'weight' => 'decimal:2',
            'length' => 'decimal:2',
            'width' => 'decimal:2',
            'height' => 'decimal:2',
            'quantity' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function mainImage(): ?ProductImage
    {
        return $this->images->firstWhere('is_main', true) ?? $this->images->first();
    }

    public function editorDescription(): string
    {
        $description = (string) ($this->description ?? '');

        if ($description === '') {
            return '';
        }

        if (str_contains($description, 'rt-editor') && preg_match('/<div[^>]*class="rt-editor"[^>]*>(.*?)<\/div>/is', $description, $matches)) {
            return trim($matches[1]);
        }

        if (str_contains($description, 'rt-toolbar')) {
            return '';
        }

        return $description;
    }
}

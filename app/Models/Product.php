<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
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

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * @param  array<int, int>  $categoryIds
     */
    public function scopeInCategories(Builder $query, array $categoryIds): Builder
    {
        if ($categoryIds === []) {
            return $query;
        }

        return $query->whereHas(
            'categories',
            fn (Builder $categoryQuery) => $categoryQuery->whereIn('categories.id', $categoryIds)
        );
    }

    public function scopePriceBetween(Builder $query, ?float $min, ?float $max): Builder
    {
        if ($min !== null) {
            $query->where('price', '>=', $min);
        }

        if ($max !== null) {
            $query->where('price', '<=', $max);
        }

        return $query;
    }

    /**
     * Matches `name`, `short_description`, `description` and `sku` against
     * a user-supplied search term.
     *
     * On MySQL/MariaDB this uses a native FULLTEXT index (boolean mode, so
     * results are relevance-ranked and can use the index instead of a table
     * scan). Every other driver — SQLite locally, SQLite in tests — falls
     * back to an escaped LIKE search, since FULLTEXT has no equivalent there.
     *
     * LIKE metacharacters (%, _, \) in the term are escaped so a search for
     * "50%" or "a_b" matches those literal characters instead of being
     * interpreted as wildcards.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        $driver = $query->getModel()->getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true) && mb_strlen($term) >= 3) {
            return $query->where(function (Builder $q) use ($term) {
                $q->whereFullText(['name', 'short_description', 'description'], $term)
                    ->orWhere('sku', 'like', self::escapeLike($term).'%');
            });
        }

        $escaped = self::escapeLike($term);

        return $query->where(function (Builder $q) use ($escaped) {
            $q->where('name', 'like', "%{$escaped}%")
                ->orWhere('short_description', 'like', "%{$escaped}%")
                ->orWhere('description', 'like', "%{$escaped}%")
                ->orWhere('sku', 'like', "%{$escaped}%");
        });
    }

    public static function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }
}

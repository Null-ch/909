<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'parent_id',
    'name',
    'slug',
    'description',
    'image',
    'is_active',
    'sort_order',
    'meta_title',
    'meta_description',
])]
class Category extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    public function editorDescription(): string
    {
        $description = (string) ($this->description ?? '');

        if ($description === '') {
            return '';
        }

        if (str_contains($description, 'rt-editor')) {
            if (preg_match('/<div[^>]*class="rt-editor"[^>]*>(.*?)<\/div>/is', $description, $matches)) {
                return trim($matches[1]);
            }
        }

        if (str_contains($description, 'rt-toolbar')) {
            return '';
        }

        return $description;
    }

    public function plainDescription(): string
    {
        $description = (string) ($this->description ?? '');

        if ($description === '') {
            return '';
        }

        if (str_contains($description, 'rt-editor')) {
            if (preg_match('/<div[^>]*class="rt-editor"[^>]*>(.*?)<\/div>/is', $description, $matches)) {
                return trim(strip_tags(html_entity_decode($matches[1])));
            }
        }

        return trim(strip_tags($description));
    }
}

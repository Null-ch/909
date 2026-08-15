<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'delivery_method_id',
    'name',
    'min_weight',
    'max_weight',
    'min_volume',
    'max_volume',
    'max_length',
    'max_width',
    'max_height',
    'price',
    'is_active',
    'sort_order',
])]
class DeliveryRate extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'min_weight' => 'decimal:2',
            'max_weight' => 'decimal:2',
            'min_volume' => 'decimal:4',
            'max_volume' => 'decimal:4',
            'max_length' => 'decimal:2',
            'max_width' => 'decimal:2',
            'max_height' => 'decimal:2',
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function method(): BelongsTo
    {
        return $this->belongsTo(DeliveryMethod::class, 'delivery_method_id');
    }

    public function label(): string
    {
        if ($this->name) {
            return $this->name;
        }

        $parts = [];

        if ($this->max_weight !== null) {
            $parts[] = "{$this->min_weight}–{$this->max_weight} кг";
        } else {
            $parts[] = "от {$this->min_weight} кг";
        }

        if ($this->max_volume !== null) {
            $parts[] = "до {$this->max_volume} м³";
        }

        return implode(', ', $parts);
    }
}

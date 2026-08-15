<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['product_id', 'attribute_name', 'attribute_value'])]
class ProductAttribute extends Model
{
    use SoftDeletes;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

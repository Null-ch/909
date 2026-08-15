<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'user_id',
    'order_number',
    'total_price',
    'delivery_price',
    'status',
    'payment_status',
    'customer_name',
    'customer_phone',
    'customer_email',
    'delivery_address',
    'comment',
    'stock_deducted',
    'delivery_method_id',
])]
class Order extends Model
{
    use SoftDeletes;

    protected $attributes = [
        'status' => 'new',
        'payment_status' => 'pending',
    ];

    protected function casts(): array
    {
        return [
            'total_price' => 'decimal:2',
            'delivery_price' => 'decimal:2',
            'status' => OrderStatus::class,
            'payment_status' => PaymentStatus::class,
            'stock_deducted' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function deliveryMethod(): BelongsTo
    {
        return $this->belongsTo(DeliveryMethod::class);
    }

    public function customerLabel(): string
    {
        if ($this->user) {
            return $this->user->name.' ('.$this->user->email.')';
        }

        return $this->customer_name;
    }

    public function grandTotal(): string
    {
        return number_format((float) $this->total_price + (float) $this->delivery_price, 2, '.', ' ');
    }
}

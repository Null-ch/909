<?php

namespace App\Enums;

enum OrderStatus: string
{
    case New = 'new';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Новый',
            self::Processing => 'В обработке',
            self::Shipped => 'Отправлен',
            self::Delivered => 'Доставлен',
            self::Cancelled => 'Отменён',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::New => 'badge-blue',
            self::Processing => 'badge-yellow',
            self::Shipped => 'badge-teal',
            self::Delivered => 'badge-green',
            self::Cancelled => 'badge-red',
        };
    }

    public function bootstrapBadgeClass(): string
    {
        return match ($this) {
            self::New => 'bg-primary',
            self::Processing => 'bg-warning text-dark',
            self::Shipped => 'bg-info text-dark',
            self::Delivered => 'bg-success',
            self::Cancelled => 'bg-danger',
        };
    }
}

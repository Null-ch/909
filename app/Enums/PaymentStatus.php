<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Ожидает оплаты',
            self::Paid => 'Оплачен',
            self::Failed => 'Ошибка оплаты',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'badge-yellow',
            self::Paid => 'badge-green',
            self::Failed => 'badge-red',
        };
    }
}

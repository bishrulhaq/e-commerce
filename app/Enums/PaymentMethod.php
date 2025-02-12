<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CARD = 'card';
    case CASH = 'cash';
    case PAY_ON_DELIVERY = 'pay_on_delivery';
    case OTHER = 'other';

    public static function values(): array
    {
        return [
            self::CARD->value,
            self::CASH->value,
            self::PAY_ON_DELIVERY->value,
            self::OTHER->value,
        ];
    }
}

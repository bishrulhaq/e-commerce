<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELED = 'canceled';

    public static function getStatuses(): array
    {
        return [
            self::PENDING->value => 'Pending',
            self::PROCESSING->value => 'Processing',
            self::SHIPPED->value => 'Shipped',
            self::DELIVERED->value => 'Delivered',
            self::CANCELED->value => 'Canceled',
        ];
    }
}

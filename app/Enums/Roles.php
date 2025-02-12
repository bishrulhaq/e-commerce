<?php

namespace App\Enums;

enum Roles: int
{
    case ADMIN = 1;
    case CUSTOMER = 2;
    case MANAGER = 3;
    case SUPPORT = 4;

    public static function getRoles(): array
    {
        return [
            self::ADMIN->value => 'Admin',
            self::CUSTOMER->value => 'Customer',
            self::MANAGER->value => 'Manager',
            self::SUPPORT->value => 'Support',
        ];
    }
}

<?php

namespace App\Enum;

use App\Enum\Traits\EnumToArray;

enum Role: int
{
    use EnumToArray;

    case SUPER_ADMIN = 0;
    case ADMIN       = 1;
    case USER        = 2;

    public function level(): int
    {
        return match ($this) {
            self::SUPER_ADMIN => 99,
            self::ADMIN       => 80,
            self::USER        => 10,
        };
    }
}

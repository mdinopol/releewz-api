<?php

namespace App\Enum\Traits;

trait EnumToArray
{
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }

    /**
     * @throws \Exception
     */
    public static function random(): self
    {
        return self::cases()[random_int(0, count(self::cases()) - 1)];
    }
}

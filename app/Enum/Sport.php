<?php

namespace App\Enum;

use App\Enum\Traits\EnumToArray;

enum Sport: string
{
    use EnumToArray;

    case BASKETBALL        = 'basketball';
    case SOCCER            = 'soccer';
    case TENNIS            = 'tennis';
    case DARTS             = 'darts';
    case CYCLING           = 'cycling';
    case AMERICAN_FOOTBALL = 'american_football';
    case VOLLEYBALL        = 'volleyball';
    case RUGBY             = 'rugby';
    case BASEBALL          = 'baseball';
    case FORMULA_ONE       = 'formula_one';
    case BOXING            = 'boxing';

    public function template(): array
    {
        return config('sportachievements.'.$this->value) ?? [];
    }

    /**
     * @return Sport[]
     */
    public static function active()
    {
        $sports = [];

        foreach (array_keys(config('sportachievements')) as $sport) {
            $sports[] = self::tryFrom($sport);
        }

        return $sports;
    }

    public function isActive(): bool
    {
        return in_array($this, self::active());
    }
}

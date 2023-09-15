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
        return config('scoretemplates.'.$this->value) ?? [];
    }
}

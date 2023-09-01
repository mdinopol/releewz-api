<?php

namespace App\Enum;

use App\Enum\Traits\EnumToArray;

enum License: string
{
    use EnumToArray;

    case MALTA_EUR   = 'malta_eur';
    case US_USD      = 'us_usd';
    case DENMARK_DKK = 'denmark_dkk';
    case UK_GBP      = 'uk_gbp';
}

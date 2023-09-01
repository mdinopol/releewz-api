<?php

namespace App\Enum;

use App\Enum\Traits\EnumToArray;

enum GameDuration: string
{
    use EnumToArray;

    case SPAN  = 'span';
    case DAILY = 'daily';
}

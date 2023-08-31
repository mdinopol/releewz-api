<?php

namespace App\Enum;

use App\Enum\Traits\EnumToArray;

enum GameState: int
{
    use EnumToArray;

    case IN_SETUP          = 0;
    case OPEN_REGISTRATION = 1;
    case PRE_LIVE          = 2;
    case LIVE              = 3;
    case FINISH            = 4;
    case CANCEL            = 5;
}

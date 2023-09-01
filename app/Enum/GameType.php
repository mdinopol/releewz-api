<?php

namespace App\Enum;

use App\Enum\Traits\EnumToArray;

enum GameType: string
{
    use EnumToArray;

    case REGULAR_SEASON  = 'regular_season';
    case QUALIFIER       = 'qualifier';
    case DIVISION_FINALS = 'division_finals';
    case FINALS          = 'finals';
    case HEAD_TO_HEAD    = 'head_to_head';
}

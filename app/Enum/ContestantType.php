<?php

namespace App\Enum;

use App\Enum\Traits\EnumToArray;

enum ContestantType: string
{
    use EnumToArray;

    case TEAM        = 'team';
    case INDIVIDUAL  = 'individual'; // Sportsman from a teamless sport(e.g., Tennis)
    case TEAM_MEMBER = 'team_member'; // Also an INDIVIDUAL but belongs to a team
}

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
    case DISCARD           = 6;

    /**
     * Game is immutable once its state is open to public.
     */
    public function isImmutable(): bool
    {
        return in_array($this, [
            self::OPEN_REGISTRATION,
            self::PRE_LIVE,
            self::LIVE,
            self::FINISH,
            self::CANCEL,
        ]);
    }
}

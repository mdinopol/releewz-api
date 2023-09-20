<?php

namespace App\Observers;

use App\Enum\GameState;
use App\Models\Game;

class GameObserver
{
    public function creating(Game $game): void
    {
        $game->game_state = GameState::getDefault();
    }
}

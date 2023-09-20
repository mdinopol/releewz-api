<?php

namespace App\Services;

use App\Models\Game;
use App\Models\User;

class GameService
{
    public function createUserEntryForGame(User $user, Game $game, array $data): void
    {
        $game->users()->attach($user, $data);
    }
}

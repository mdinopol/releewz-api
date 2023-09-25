<?php

namespace App\Policies;

use App\Enum\GameState;
use App\Enum\Role;
use App\Models\Game;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GamePolicy
{
    public function modify(User $user, Game $game): Response|bool
    {
        return $this->modificationRule($game);
    }

    public function createEntry(User $user, Game $game): Response|bool
    {
        if ($user->role->level() >= Role::USER->level()) {
            if ($game->game_state->level() > GameState::OPEN_REGISTRATION->level()) {
                return Response::deny('Entry creation for is now closed.');
            }
        }

        return true;
    }

    protected function modificationRule(Game $game): Response|bool
    {
        if ($game->isImmutable() === true) {
            return Response::deny('Unable to perform action. The game is already open to public.');
        }

        return true;
    }
}

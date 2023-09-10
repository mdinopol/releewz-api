<?php

namespace App\Policies;

use App\Enum\GameState;
use App\Models\Game;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class GamePolicy
{
    use HandlesAuthorization;
    /**
     * Perform pre-authorization checks.
     *
     * @param User   $user
     * @param string $ability
     *
     * @return void|bool
     */
    public function before(User $user, string $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function update(User $user, Game $game): Response|bool
    {
        /**
         * @var GameState $currentGameState
         */
        $currentGameState = $game->game_state;

        if ($currentGameState->isImmutable()) {
            if (request()->hasAny($this->immutableFields())) {
                return Response::deny("Cannot perform an update. Game is already public.");
            }

            if (
                request()->has('game_state') &&
                ($toGameState = GameState::tryFrom(request()->get('game_state')))
            ) {
                return $toGameState->level() < $currentGameState->level()
                    ? Response::deny("Can't revert game to its previous state.")
                    : true;
            }

            return false;
        }

        return true;
    }

    public function delete(User $user, Game $game): Response|bool
    {
        /**
         * @var GameState $gameState
         */
        $gameState = $game->game_state;

        if ($gameState->isImmutable()) {
            return Response::deny("Can't delete a game that is already or had been opened to public.");
        }

        return true;
    }

    private function immutableFields(): array
    {
        return [
            'tournament_id',
            'name',
            'short',
            'slug',
            'description',
            'sport',
            'duration_type',
            'game_type',
            'min_entry',
            'max_entry',
            'entry_contestants',
            'max_entry_value',
            'entry_price',
            'initial_prize_pool',
            'current_prize_pool',
            'start_date',
            'end_date',
            'point_template',
        ];
    }
}

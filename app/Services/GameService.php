<?php

namespace App\Services;

use App\Enum\GameState;
use App\Models\Game;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class GameService
{
    public function updateGameState(Game $game, GameState $toGameState): Game
    {
        if ($game->isImmutable() && $toGameState->level() < $game->game_state->level()) {
            abort(HttpResponse::HTTP_FORBIDDEN, "Can't revert game to its previous state");
        }

        if (!$game->tournament) {
            abort(HttpResponse::HTTP_FORBIDDEN, 'Assign game to a tournament first, and try again.');
        }

        $game->game_state = $toGameState;
        $game->save();

        return $game->fresh();
    }

    /**
     * @param Game $game
     * @param Collection|array $contestants
     */
    public function syncStartlist($game, $contestants): void
    {
        $game->contestants()->sync($contestants);
    }
}

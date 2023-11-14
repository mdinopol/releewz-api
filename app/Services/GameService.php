<?php

namespace App\Services;

use App\Data\PointTemplate;
use App\Enum\GameState;
use App\Models\Game;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class GameService
{
    public function updateGameState(Game $game, GameState $toGameState): Game
    {
        if ($game->isImmutable() && $toGameState->level() < $game->game_state->level()) {
            abort(HttpResponse::HTTP_UNPROCESSABLE_ENTITY, "Can't revert game to its previous state");
        }

        if (!$game->tournament) {
            abort(HttpResponse::HTTP_UNPROCESSABLE_ENTITY, 'Assign game to a tournament first, and try again.');
        }

        $game->game_state = $toGameState;
        $game->save();

        return $game->fresh();
    }

    public function syncStartlist(Game $game, array $contestants = []): void
    {
        $startlist = [];

        if (count($contestants) > 0) {
            foreach ($contestants as $contestant) {
                $startlist[$contestant['id']] = ['value' => $contestant['value'] ?? null];
            }

            unset($contestants);
        }

        $game->contestants()->sync($startlist);
    }

    public function setPointTemplate(PointTemplate $filledTemplate): Game
    {
        if (!$filledTemplate->isValid()) {
            abort(HttpResponse::HTTP_UNPROCESSABLE_ENTITY, 'Unable to set template.');
        }

        $game = $filledTemplate->game;

        $game->points_template = $filledTemplate->format();
        $game->save();

        return $game->fresh();
    }
}

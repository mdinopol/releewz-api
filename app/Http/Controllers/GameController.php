<?php

namespace App\Http\Controllers;

use App\Enum\GameState;
use App\Http\Requests\CreateEntryRequest;
use App\Http\Requests\UpsertGameRequest;
use App\Models\Game;
use App\Services\GameService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class GameController extends Controller
{
    public function index(): LengthAwarePaginator
    {
        return Game::inRegistration()
            ->withCount([
                'users',
            ])
            ->paginate(10);
    }

    // TO DO
    // public function mine(Request $request): Collection
    // {
    //     return $request->user()->games->live()->get();
    // }

    public function store(UpsertGameRequest $request): Game
    {
        return Game::create($request->validated());
    }

    public function show(Game $game): Game
    {
        return $game->loadCount([
            'users',
        ])->load([
            'tournament',
            'bouts',
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UpsertGameRequest $request, Game $game): Game
    {
        if ($game->isImmutable()) {
            abort(HttpResponse::HTTP_FORBIDDEN, 'Cannot perform an update. Game is already public.');
        }

        $game->update($request->validated());

        return $game->fresh();
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Game $game): array
    {
        if ($game->isImmutable()) {
            abort(HttpResponse::HTTP_FORBIDDEN, "Can't delete a game that is already or had been opened to public.");
        }

        $game->delete();

        return [];
    }

    public function createUserEntry(CreateEntryRequest $request, Game $game): void
    {
        $game->users()->attach(
            $request->user(),
            Arr::except($request->validated(), ['user_id', 'game_id'])
        );
    }

    public function updateGameState(GameService $gameService, Game $game, GameState $gameState): Game
    {
        return $gameService->updateGameState($game, $gameState);
    }
}

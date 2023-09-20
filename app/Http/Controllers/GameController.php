<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEntryRequest;
use App\Http\Requests\UpsertGameRequest;
use App\Models\Game;
use App\Services\GameService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

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
        $this->authorize('update', $game);

        $game->update($request->validated());

        return $game->fresh();
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Game $game): array
    {
        $this->authorize('delete', $game);

        $game->delete();

        return [];
    }

    public function createEntry(CreateEntryRequest $request, Game $game, GameService $gameService): void
    {
        $gameService->createUserEntryForGame(
            $request->user(),
            $game,
            Arr::except($request->validated(), ['user_id', 'game_id'])
        );
    }
}

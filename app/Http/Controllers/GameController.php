<?php

namespace App\Http\Controllers;

use App\Enum\GameState;
use App\Enum\Sport;
use App\Http\Requests\CreateEntryRequest;
use App\Http\Requests\SyncStartlistRequest;
use App\Http\Requests\UpsertGameRequest;
use App\Models\Game;
use App\Services\GameService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class GameController extends Controller
{
    public function index(GameState $gameState, ?string $sport = null): LengthAwarePaginator
    {
        return Game::filters($gameState, Sport::tryFrom($sport))
            ->withCount([
                'users',
            ])
            ->with(['tournament'])
            ->paginate(10);
    }

    public function store(UpsertGameRequest $request): Game
    {
        return Game::create($request->validated());
    }

    public function show(Game $game): Game
    {
        return $game->load([
            'users',
            'tournament',
            'bouts',
            'contestants',
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UpsertGameRequest $request, Game $game): Game
    {
        $this->authorize('modify', $game);

        $game->update($request->validated());

        return $game->fresh();
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Game $game): array
    {
        $this->authorize('modify', $game);

        $game->delete();

        return [];
    }

    /**
     * @throws AuthorizationException
     */
    public function createUserEntry(CreateEntryRequest $request, Game $game): void
    {
        $this->authorize('createEntry', $game);

        $game->users()->attach(
            $request->user(),
            Arr::except($request->validated(), ['user_id', 'game_id'])
        );
    }

    public function myEntries(Request $request, GameState $gameState): LengthAwarePaginator
    {
        return $request->user()->games()->state($gameState)->paginate(10);
    }

    public function updateGameState(GameService $gameService, Game $game, GameState $gameState): Game
    {
        return $gameService->updateGameState($game, $gameState);
    }

    /**
     * @throws AuthorizationException
     */
    public function syncStartlist(SyncStartlistRequest $request, Game $game): void
    {
        $this->authorize('modify', $game);

        $game->contestants()->sync($request->validated()['contestants']);
    }
}

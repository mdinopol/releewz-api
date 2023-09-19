<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertMatchupRequest;
use App\Models\Matchup;
use Illuminate\Database\Eloquent\Collection;

class MatchupController extends Controller
{
    public function index(): Collection
    {
        return Matchup::all();
    }

    public function store(UpsertMatchupRequest $request): Matchup
    {
        return Matchup::create($request->validated());
    }

    public function show(Matchup $matchup): Matchup
    {
        return $matchup->load([
            'bout',
            'scores',
            'home',
            'away',
        ]);
    }

    public function update(UpsertMatchupRequest $request, Matchup $matchup): Matchup
    {
        $matchup->update($request->validated());

        return $matchup->fresh();
    }

    public function destroy(Matchup $matchup): array
    {
        $matchup->delete();

        return [];
    }
}

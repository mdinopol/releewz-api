<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertTournamentRequest;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Collection;

class TournamentController extends Controller
{
    public function index(): Collection
    {
        return Tournament::all();
    }

    public function store(UpsertTournamentRequest $request): Tournament
    {
        return Tournament::create($request->validated());
    }

    public function show(Tournament $tournament): Tournament
    {
        return $tournament;
    }

    public function update(UpsertTournamentRequest $request, Tournament $tournament): Tournament
    {
        $tournament->update($request->validated());

        return $tournament->fresh();
    }

    public function destroy(Tournament $tournament): array
    {
        $tournament->delete();

        return [];
    }
}

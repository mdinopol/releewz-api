<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertAchievementRequest;
use App\Models\Achievement;
use Illuminate\Database\Eloquent\Collection;

class AchievementController extends Controller
{
    public function index(): Collection
    {
        return Achievement::all();
    }

    public function store(UpsertAchievementRequest $request): Achievement
    {
        return Achievement::create($request->validated());
    }

    public function show(Achievement $achievement): Achievement
    {
        return $achievement;
    }

    public function update(UpsertAchievementRequest $request, Achievement $achievement): Achievement
    {
        $achievement->update($request->validated());

        return $achievement->fresh();
    }

    public function destroy(Achievement $achievement): array
    {
        $achievement->delete();

        return [];
    }

    // TO DO: Destroy should only be executed if it's not in used by at least one game with immutable game state
    // TO DO: Update should only be allowed if it's not used in any games of all state
}

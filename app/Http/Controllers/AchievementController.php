<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertAchievementRequest;
use App\Models\Achievement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertContestantRequest;
use App\Models\Contestant;
use Illuminate\Database\Eloquent\Collection;

class ContestantController extends Controller
{
    public function index(): Collection
    {
        return Contestant::all();
    }

    public function store(UpsertContestantRequest $request): Contestant
    {
        return Contestant::create($request->validated());
    }

    public function show(Contestant $contestant): Contestant
    {
        return $contestant;
    }

    public function update(UpsertContestantRequest $request, Contestant $contestant): Contestant
    {
        $contestant->update($request->validated());

        return $contestant->fresh();
    }

    public function destroy(Contestant $contestant): array
    {
        $contestant->delete();

        return [];
    }
}

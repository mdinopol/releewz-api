<?php

namespace App\Http\Controllers;

use App\Http\Requests\GiveScoreRequest;
use App\Http\Requests\UpsertMattchRequest;
use App\Models\Mattch;
use App\Services\MattchService;
use Illuminate\Database\Eloquent\Collection;

class MattchController extends Controller
{
    public function index(): Collection
    {
        return Mattch::all();
    }

    public function store(UpsertMattchRequest $request): Mattch
    {
        return Mattch::create($request->validated());
    }

    public function show(Mattch $mattch): Mattch
    {
        return $mattch->load([
            'tournament',
            'home',
            'away',
        ]);
    }

    public function update(UpsertMattchRequest $request, Mattch $mattch): Mattch
    {
        $mattch->update($request->validated());

        return $mattch->fresh();
    }

    public function destroy(Mattch $mattch): array
    {
        $mattch->delete();

        return [];
    }

    public function giveScore(MattchService $mattchService, GiveScoreRequest $request, Mattch $mattch): Mattch
    {
        $mattchService->giveScore($mattch, $request->validated());

        return $mattch->fresh([
            'tournament',
            'home',
            'away',
            'scores',
        ]);
    }
}

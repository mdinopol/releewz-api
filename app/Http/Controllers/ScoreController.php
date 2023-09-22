<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpsertScoreRequest;
use App\Models\Score;

class ScoreController extends Controller
{
    public function store(UpsertScoreRequest $request): Score
    {
        return Score::create($request->validated());
    }

    public function show(Score $score): Score
    {
        return $score;
    }

    public function update(UpsertScoreRequest $request, Score $score): Score
    {
        $score->update($request->validated());

        return $score->fresh();
    }
}

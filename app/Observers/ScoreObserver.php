<?php

namespace App\Observers;

use App\Models\Score;

class ScoreObserver
{
    public function updating(Score $score): void
    {
        $previousValues = $score->getOriginal();
        $history = $score->history ?? [];
        $history[] = [
            'home_score'  => $previousValues['home_score'],
            'home_points' => $previousValues['home_points'],
            'away_score'  => $previousValues['away_score'],
            'away_points' => $previousValues['away_points'],
            'updated_at'  => $previousValues['updated_at'],
        ];

        $score->history = $history;
    }
}

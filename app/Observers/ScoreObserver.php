<?php

namespace App\Observers;

use App\Models\Score;

class ScoreObserver
{
    public function updating(Score $score): void
    {
        $previousValues = $score->getOriginal();
        $history        = $score->history ?? [];
        $history[]      = [
            'home_score' => $previousValues['home_score'],
            'away_score' => $previousValues['away_score'],
            'updated_at' => $previousValues['updated_at'],
        ];

        $score->history = $history;
    }
}

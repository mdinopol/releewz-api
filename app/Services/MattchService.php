<?php

namespace App\Services;

use App\Models\Mattch;
use App\Models\Score;

class MattchService
{
    public function giveScore(Mattch $mattch, array $results): void
    {
        foreach ($results['score'] as $result) {
            // Do not mass upsert to avoid bypassing Score model's update observer.
            Score::updateOrCreate(
                ['mattch_id' => $mattch->id, 'achievement' => $result['achievement']],
                ['home_score' => $result['home'], 'away_score' => $result['away']]
            );
        }
    }
}

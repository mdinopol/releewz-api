<?php

namespace App\Observers;

use App\Models\Skhedule;
use App\Models\Tournament;
use Carbon\CarbonPeriod;

class TournamentObserver
{
    public function created(Tournament $tournament): void
    {
        // create schedules
        $schedules = [];

        foreach (CarbonPeriod::create($tournament->start_date, $tournament->end_date) as $day) {
            $schedules[] = [
                'tournament_id' => $tournament->id,
                'name'          => $day->format('d F'),
                'date'          => $day->toDate(),
            ];
        }

        Skhedule::insert($schedules);
    }
}

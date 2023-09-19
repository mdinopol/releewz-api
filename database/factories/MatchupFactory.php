<?php

namespace Database\Factories;

use App\Models\Bout;
use App\Models\Contestant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Matchup>
 */
class MatchupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bout_id'    => Bout::factory(),
            'home_id'    => Contestant::factory(),
            'away_id'    => Contestant::factory(),
            'start_date' => Carbon::now()->addMinutes(2)->addSecond(),
            'end_date'   => Carbon::now()->addMinutes(2)->addHour(),
        ];
    }
}

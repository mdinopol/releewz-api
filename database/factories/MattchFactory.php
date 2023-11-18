<?php

namespace Database\Factories;

use App\Models\Contestant;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mattch>
 */
class MattchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tournament_id' => Tournament::factory(),
            'home_id'       => Contestant::factory(),
            'away_id'       => Contestant::factory(),
            'start_date'    => now()->addMinutes(2)->addSecond(),
            'end_date'      => now()->addMinutes(2)->addHour(),
        ];
    }
}

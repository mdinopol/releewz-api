<?php

namespace Database\Factories;

use App\Enum\Achievement;
use App\Models\Mattch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Score>
 */
class ScoreFactory extends Factory
{
    public function definition(): array
    {
        return [
            'mattch_id'   => Mattch::factory(),
            'achievement' => Achievement::random()->value,
            'home_score'  => fake()->numberBetween(1, 10),
            'home_points' => fake()->numberBetween(10, 20),
            'away_score'  => fake()->numberBetween(1, 10),
            'away_points' => fake()->numberBetween(10, 20),
        ];
    }
}

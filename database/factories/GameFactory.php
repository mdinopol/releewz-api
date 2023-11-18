<?php

namespace Database\Factories;

use App\Enum\ContestantType;
use App\Enum\GameDuration;
use App\Enum\GameState;
use App\Enum\GameType;
use App\Enum\Sport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tournament_id'      => null,
            'name'               => fake()->unique()->word(),
            'short'              => \Str::random(5),
            'slug'               => fake()->unique()->slug(),
            'description'        => fake()->sentence(),
            'sport'              => Sport::random(),
            'game_state'         => GameState::random(),
            'duration_type'      => GameDuration::random(),
            'game_type'          => GameType::random(),
            'contestant_type'    => ContestantType::random(),
            'min_entry'          => rand(5, 10),
            'max_entry'          => fake()->numberBetween(15, 500),
            'entry_contestants'  => fake()->numberBetween(5, 20),
            'max_entry_value'    => fake()->randomFloat('2', 100),
            'entry_price'        => fake()->randomFloat('2', 1, 10),
            'initial_prize_pool' => fake()->randomFloat('2', 50),
            'current_prize_pool' => fake()->randomFloat('2', 50),
            'start_date'         => Carbon::now()->addWeek(),
            'end_date'           => Carbon::now()->addYear(),
            'point_template'     => null,
        ];
    }
}

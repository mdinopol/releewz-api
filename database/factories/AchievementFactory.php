<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Achievement>
 */
class AchievementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'        => fake()->unique()->title(),
            'alias'       => fake()->word(),
            'short'       => \Str::random(5),
            'order'       => 0,
            'is_range'    => false,
            'description' => fake()->sentence(),
        ];
    }
}
<?php

namespace Database\Factories;

use App\Enum\ContestantType;
use App\Enum\Country;
use App\Enum\Sport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contestant>
 */
class ContestantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parent_id'       => null,
            'name'            => fake()->unique()->name(),
            'alias'           => fake()->lastName(),
            'country_code'    => Country::random(),
            'contestant_type' => ContestantType::random(),
            'sport'           => Sport::random(),
            'active'          => true,
            'image_path'      => fake()->imageUrl(),
        ];
    }
}

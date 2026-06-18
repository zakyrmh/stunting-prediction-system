<?php

namespace Database\Factories;

use App\Models\Posyandu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Posyandu>
 */
class PosyanduFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Posyandu ' . fake()->streetName(),
            'address' => fake()->address(),
            'village' => fake()->words(2, true),
            'district' => fake()->city(),
            'city' => fake()->city(),
        ];
    }
}

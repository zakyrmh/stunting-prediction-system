<?php

namespace Database\Factories;

use App\Models\Prediction;
use App\Models\Children;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prediction>
 */
class PredictionFactory extends Factory
{
    protected $model = Prediction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'child_id' => Children::factory(),
            'posyandu_id' => Posyandu::factory(),
            'recorded_by' => User::factory(),
            'session_id' => null,
            'weight' => fake()->randomFloat(2, 5, 25),
            'height' => fake()->randomFloat(2, 50, 110),
            'age_months' => fake()->numberBetween(0, 59),
            'examined_at' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'result' => fake()->randomElement(['normal', 'stunting_risk', 'stunted', 'severely_stunted']),
            'confidence' => fake()->randomFloat(4, 0.5, 1.0),
            'notes' => fake()->sentence(),
        ];
    }
}

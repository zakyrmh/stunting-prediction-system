<?php

namespace Database\Factories;

use App\Models\Intervention;
use App\Models\Prediction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Intervention>
 */
class InterventionFactory extends Factory
{
    protected $model = Intervention::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'prediction_id' => Prediction::factory(),
            'recommendation' => fake()->paragraph(),
            'status' => fake()->randomElement(['pending', 'in_progress', 'done', 'cancelled']),
            'follow_up_date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'follow_up_notes' => fake()->sentence(),
            'handled_by' => User::factory(),
        ];
    }
}

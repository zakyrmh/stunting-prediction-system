<?php

namespace Database\Factories;

use App\Models\Children;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Children>
 */
class ChildrenFactory extends Factory
{
    protected $model = Children::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'posyandu_id' => Posyandu::factory(),
            'nik' => fake()->unique()->numerify('16################'),
            'name' => fake()->name(),
            'birth_date' => fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'birth_place' => fake()->city(),
            'gender' => fake()->randomElement(['male', 'female']),
            'address' => fake()->address(),
        ];
    }
}

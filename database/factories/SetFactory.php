<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Set>
 */
class SetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'home_forwarder_id' => User::factory(),
            'guest_forwarder_id' => User::factory(),
            'home_goals' => fake()->randomDigitNot(2),
            'guest_goals' => fake()->randomDigitNot(2),
        ];
    }
}

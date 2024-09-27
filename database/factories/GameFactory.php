<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Team;
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
            'location_id' => Location::factory(),
            'home_team_id' => Team::factory(),
            'guest_team_id' => Team::factory(),
            'mode' => fake()->randomElement(['Single', 'BestOfThree', 'BestOfFive']),
            'winner' => fake()->randomElement(['home', 'guest']),
            'kickoff_at' => now(),
            'finished_at' => now(),
        ];
    }

    /**
     * Indicate that the game is finished.
     */
    public function finished(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'finished_at' => now(),
                'winner' => fake()->randomElement(['home', 'guest']),
            ];
        });
    }

    /**
     * Indicate that the game is running.
     */
    public function running(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'finished_at' => null,
                'winner' => null,
            ];
        });
    }

    /**
     * Indicate that the game is a single mode.
     */
    public function single(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'mode' => 'Single',
            ];
        });
    }

    /**
     * Indicate that the game is a single mode.
     */
    public function bestOfThree(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'mode' => 'BestOfThree',
            ];
        });
    }

    /**
     * Indicate that the game is a single mode.
     */
    public function bestOfFive(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'mode' => 'BestOfFive',
            ];
        });
    }
}

<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Set;
use App\Models\Team;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->single();
        $this->bestOfThree();
        $this->bestOfFive();
        $this->running();
    }

    protected function bestOfThree(): void
    {
        Game::factory()
            ->has(
                Set::factory()
                    ->count(3)
                    ->state(function (array $attributes, Game $game) {
                        return [
                            'home_forwarder_id'  => fake()->randomElement($game->homeTeam->players()),
                            'guest_forwarder_id' => fake()->randomElement($game->guestTeam->players()),
                        ];
                    })
            )
            ->state(function (array $attributes) {
                return [
                    'home_team_id'  => Team::all()->random(),
                    'guest_team_id' => Team::all()->random(),
                ];
            })
            ->finished()
            ->bestOfThree()
            ->create();
    }

    protected function bestOfFive(): void
    {
        Game::factory()
            ->has(
                Set::factory()
                    ->count(3)
                    ->state(function (array $attributes, Game $game) {
                        return [
                            'home_forwarder_id'  => fake()->randomElement($game->homeTeam->players()),
                            'guest_forwarder_id' => fake()->randomElement($game->guestTeam->players()),
                        ];
                    })
            )
            ->state(function (array $attributes) {
                return [
                    'home_team_id'  => Team::all()->random(),
                    'guest_team_id' => Team::all()->random(),
                ];
            })
            ->finished()
            ->bestOfFive()
            ->create();
    }

    protected function running(): void
    {
        Game::factory()
            ->has(
                Set::factory()
                    ->count(1)
                    ->state(function (array $attributes, Game $game) {
                        return [
                            'home_forwarder_id'  => fake()->randomElement($game->homeTeam->players()),
                            'guest_forwarder_id' => fake()->randomElement($game->guestTeam->players()),
                        ];
                    })
            )
            ->state(function (array $attributes) {
                return [
                    'home_team_id'  => Team::all()->random(),
                    'guest_team_id' => Team::all()->random(),
                ];
            })
            ->running()
            ->bestOfFive()
            ->create();
    }

    protected function single(): void
    {
        Game::factory()
            ->has(
                Set::factory()
                    ->count(1)
                    ->state(function (array $attributes, Game $game) {
                        return [
                            'home_forwarder_id'  => fake()->randomElement($game->homeTeam->players()),
                            'guest_forwarder_id' => fake()->randomElement($game->guestTeam->players()),
                        ];
                    })
            )
            ->state(function (array $attributes) {
                return [
                    'home_team_id'  => Team::all()->random(),
                    'guest_team_id' => Team::all()->random(),
                ];
            })
            ->finished()
            ->single()
            ->create();
    }
}

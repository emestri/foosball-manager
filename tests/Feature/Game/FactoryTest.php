<?php

namespace Tests\Feature\Game;

use App\Game\GameFactory;
use App\Game\GameManager;
use App\Game\GameMode;
use App\Models\Location;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FactoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_single_creates_game_with_single_mode()
    {
        $home = Team::factory()->create();
        $guest = Team::factory()->create();
        $location = Location::factory()->create();

        $handler = GameFactory::single($home, $guest, $location);

        $this->assertInstanceOf(GameManager::class, $handler);
        $this->assertDatabaseHas('games', [
            'home_team_id' => $home->id,
            'guest_team_id' => $guest->id,
            'location_id' => $location->id,
            'mode' => GameMode::Single->name,
        ]);
    }

    public function test_best_of_three_creates_game_with_best_of_three_mode()
    {
        $home = Team::factory()->create();
        $guest = Team::factory()->create();
        $location = Location::factory()->create();

        $handler = GameFactory::bestOfThree($home, $guest, $location);

        $this->assertInstanceOf(GameManager::class, $handler);
        $this->assertDatabaseHas('games', [
            'home_team_id' => $home->id,
            'guest_team_id' => $guest->id,
            'location_id' => $location->id,
            'mode' => GameMode::BestOfThree->name,
        ]);
    }

    public function test_best_of_five_creates_game_with_best_of_five_mode()
    {
        $home = Team::factory()->create();
        $guest = Team::factory()->create();
        $location = Location::factory()->create();

        $handler = GameFactory::bestOfFive($home, $guest, $location);

        $this->assertInstanceOf(GameManager::class, $handler);
        $this->assertDatabaseHas('games', [
            'home_team_id' => $home->id,
            'guest_team_id' => $guest->id,
            'location_id' => $location->id,
            'mode' => GameMode::BestOfFive->name,
        ]);
    }
}

<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Game;
use App\Models\Location;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_returns_game_collection()
    {
        $games = Game::factory()->count(3)->create();

        $response = $this->actingAs(User::factory()->create())
            ->getJson(route('games.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'location_id',
                        'home_team_id',
                        'guest_team_id',
                        'sets',
                        'mode',
                        'winner',
                        'kickoff_at',
                        'finished_at',
                    ],
                ],
            ]);
    }

    public function test_store_creates_new_game()
    {
        $homeTeam = Team::factory()->create();
        $guestTeam = Team::factory()->create();
        $location = Location::factory()->create();
        $data = [
            'home_team_id'  => $homeTeam->id,
            'guest_team_id' => $guestTeam->id,
            'location_id'   => $location->id,
            'mode'          => 'BestOfThree',
        ];

        $response = $this->actingAs(User::factory()->create())
            ->postJson(route('games.store'), $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => [
                    'home_team_id'  => $homeTeam->id,
                    'guest_team_id' => $guestTeam->id,
                    'location_id'   => $location->id,
                    'mode'          => 'BestOfThree',
                ],
            ]);
    }

    public function test_show_returns_specific_game()
    {
        $game = Game::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->getJson(route('games.show', $game->id));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id'            => $game->id,
                    'location_id'   => $game->location_id,
                    'home_team_id'  => $game->home_team_id,
                    'guest_team_id' => $game->guest_team_id,
                    'mode'          => $game->mode->name,
                ],
            ]);
    }

    public function test_destroy_removes_game()
    {
        $game = Game::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->deleteJson(route('games.destroy', $game->id));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'Set deleted successfully']);

        $this->assertDatabaseMissing('games', ['id' => $game->id]);
    }
}

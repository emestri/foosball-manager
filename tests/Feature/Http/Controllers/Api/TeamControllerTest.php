<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class TeamControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_returns_team_collection()
    {
        $teams = Team::factory()->count(3)->create();

        $response = $this->actingAs(User::factory()->create())
            ->getJson(route('teams.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'player_one_id',
                        'player_two_id',
                    ],
                ],
            ]);
    }

    public function test_show_returns_specific_team()
    {
        $team = Team::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->getJson(route('teams.show', $team->id));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id' => $team->id,
                    'player_one_id' => $team->player_one_id,
                    'player_two_id' => $team->player_two_id,
                    'player_one' => $team->playerOne->name,
                    'player_two' => $team->playerTwo->name,
                ],
            ]);
    }
}

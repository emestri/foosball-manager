<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Game;
use App\Models\Set;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class SetControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_store_creates_new_set()
    {
        $game = Game::factory()->create();
        $data = [
            'home_forwarder_id'  => $game->homeTeam->player_one_id,
            'guest_forwarder_id' => $game->guestTeam->player_one_id,
            'home_goals'         => 2,
            'guest_goals'        => 1,
        ];

        $response = $this->actingAs(User::factory()->create())
            ->postJson(route('games.sets.store', $game->id), $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => [
                    'game_id'            => $game->getKey(),
                    'home_forwarder_id'  => $game->homeTeam->player_one_id,
                    'guest_forwarder_id' => $game->guestTeam->player_one_id,
                    'home_goals'         => 2,
                    'guest_goals'        => 1,
                ],
            ]);
    }

    public function test_update_updates_existing_set()
    {
        $game = Game::factory()->create();
        $set = Set::factory()->create(['game_id' => $game->id]);
        $data = [
            'home_forwarder_id'  => $game->homeTeam->player_one_id,
            'guest_forwarder_id' => $game->guestTeam->player_one_id,
            'home_goals'         => 2,
            'guest_goals'        => 1,
        ];

        $response = $this->actingAs(User::factory()->create())
            ->putJson(route('sets.update', $set->id), $data);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'home_forwarder_id'  => $game->homeTeam->player_one_id,
                    'guest_forwarder_id' => $game->guestTeam->player_one_id,
                    'home_goals'         => 2,
                    'guest_goals'        => 1,
                ],
            ]);
    }

    public function test_destroy_removes_set()
    {
        $game = Game::factory()->create();
        $set = Set::factory()->create(['game_id' => $game->id]);

        $response = $this->actingAs(User::factory()->create())
            ->deleteJson(route('games.sets.destroy', [$game->id, $set->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'Set deleted successfully']);

        $this->assertDatabaseMissing('sets', ['id' => $set->id]);
    }
}

<?php

namespace Tests\Feature\Services;

use App\Models\Team;
use App\Models\User;
use App\Services\TeamService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected TeamService $teamService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->teamService = new TeamService();
    }

    public function test_create_team_with_valid_users()
    {
        $playerOne = User::factory()->create();
        $playerTwo = User::factory()->create();

        $team = $this->teamService->create($playerOne, $playerTwo);

        $this->assertInstanceOf(Team::class, $team);
        $this->assertEquals($playerOne->id, $team->player_one_id);
        $this->assertEquals($playerTwo->id, $team->player_two_id);
    }

    public function test_create_team_with_user_ids()
    {
        $playerOne = User::factory()->create();
        $playerTwo = User::factory()->create();

        $team = $this->teamService->create($playerOne->id, $playerTwo->id);

        $this->assertInstanceOf(Team::class, $team);
        $this->assertEquals($playerOne->id, $team->player_one_id);
        $this->assertEquals($playerTwo->id, $team->player_two_id);
    }

    public function test_disallows_teams_with_same_users()
    {
        $playerOne = User::factory()->create();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Teams with same players are not allowed');

        $this->teamService->create($playerOne, $playerOne);
    }

    public function test_it_does_not_create_duplicate_teams()
    {
        $playerOne = User::factory()->create();
        $playerTwo = User::factory()->create();

        $this->teamService->create($playerOne, $playerTwo);
        $this->teamService->create($playerTwo, $playerOne);

        $this->assertDatabaseCount('teams', 1);
    }

    public function test_it_returns_true_if_a_user_is_part_of_a_team()
    {
        $playerOne = User::factory()->create();
        $playerTwo = User::factory()->create();

        $team = $this->teamService->create($playerOne, $playerTwo);

        $this->assertTrue($this->teamService->isUserPartOf($playerOne, $team));
        $this->assertTrue($this->teamService->isUserPartOf($playerTwo, $team));
    }

    public function test_it_returns_false_if_a_user_is_not_part_of_a_team()
    {
        $playerOne = User::factory()->create();
        $playerTwo = User::factory()->create();
        $playerThree = User::factory()->create();

        $team = $this->teamService->create($playerOne, $playerTwo);

        $this->assertFalse($this->teamService->isUserPartOf($playerThree, $team));
    }
}

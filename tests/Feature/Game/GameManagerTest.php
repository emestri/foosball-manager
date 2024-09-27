<?php

namespace Tests\Feature\Game;

use App\Events\GameFinished;
use App\Game\GameManager;
use App\Game\Modes\AbstractMode;
use App\Game\Modes\BestOfMode;
use App\Models\Game;
use App\Models\Set;
use App\Services\TeamService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use LogicException;
use Tests\TestCase;

class GameManagerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected GameManager $gameManager;
    protected Game $game;
    protected $teamService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->game = Game::factory()->create(['mode' => 'BestOfThree']);
        $this->teamService = $this->mock(TeamService::class);
        $this->gameManager = new GameManager($this->game, $this->teamService);
    }

    public function test_it_adds_set_to_game(): void
    {
        $game = Game::factory()->create(['mode' => 'Single']);

        $set = Set::factory()->make([
            'home_forwarder_id'  => $game->homeTeam->playerOne->getKey(),
            'guest_forwarder_id' => $game->guestTeam->playerOne->getKey(),
            'home_goals'  => 2,
            'guest_goals' => 4,
        ]);

        $gameManager = new GameManager($game, new TeamService);

        $gameManager->saveSet($set);

        $this->assertTrue($game->sets->contains($set));
        $this->assertDatabaseHas('games', ['id' => $game->getKey(), 'winner' => 'guest']);
    }

    public function test_it_updates_a_set_of_a_game()
    {
        $game = Game::factory()->create(['mode' => 'Single']);

        $set = Set::factory()->make([
            'home_forwarder_id'  => $game->homeTeam->playerOne->getKey(),
            'guest_forwarder_id' => $game->guestTeam->playerOne->getKey(),
            'home_goals'  => 2,
            'guest_goals' => 4,
        ]);

        $gameManager = new GameManager($game, new TeamService);

        $gameManager->saveSet($set);

        $set->fill([
            'home_forwarder_id'  => $game->homeTeam->playerTwo->getKey(),
            'guest_forwarder_id' => $game->guestTeam->playerTwo->getKey(),
            'home_goals'  => 3,
            'guest_goals' => 5,
        ]);

        $gameManager->saveSet($set);

        $this->assertTrue($game->sets->contains($set));
        $this->assertDatabaseHas('games', ['id' => $game->getKey(), 'winner' => 'guest']);
        $this->assertDatabaseHas('sets', [
            'home_forwarder_id'  => $game->homeTeam->playerTwo->getKey(),
            'guest_forwarder_id' => $game->guestTeam->playerTwo->getKey(),
            'home_goals'  => 3,
            'guest_goals' => 5,
        ]);
    }

    public function test_save_set_throws_exception_if_game_is_finished(): void
    {
        $set = Set::factory()->make();

        $mode = $this->mock(BestOfMode::class);
        $mode->shouldReceive('isFinished')->andReturn(true);

        $game = Game::factory()->create(['mode' => 'BestOfThree', 'finished_at' => null]);

        $gameManager = new GameManagerTestHelper($game, $this->teamService);
        $gameManager->setMode($mode);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Cannot add a new set, the game it is already finished.');

        $gameManager->saveSet($set);
    }

    public function test_save_set_throws_exception_if_home_forwarder_not_part_of_home_team(): void
    {
        $set = Set::factory()->make();

        $mode = $this->mock(BestOfMode::class);
        $mode->shouldReceive('isFinished')->andReturn(false);

        $game = Game::factory()->create(['mode' => 'BestOfThree', 'finished_at' => null]);

        $this->teamService->shouldReceive('isUserPartOf')
            ->with($set->home_forwarder_id, $game->homeTeam)
            ->andReturn(false);

        $gameManager = new GameManagerTestHelper($game, $this->teamService);
        $gameManager->setMode($mode);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Home forwarder is not part of the home team!");

        $gameManager->saveSet($set);
    }

    public function test_save_set_throws_exception_if_guest_forwarder_not_part_of_guest_team(): void
    {
        $set = Set::factory()->make();

        $mode = $this->mock(BestOfMode::class);
        $mode->shouldReceive('isFinished')->andReturn(false);

        $game = Game::factory()->create(['mode' => 'BestOfThree', 'finished_at' => null]);

        $this->teamService->shouldReceive('isUserPartOf')
            ->with($set->home_forwarder_id, $game->homeTeam)
            ->andReturn(true);
        $this->teamService->shouldReceive('isUserPartOf')
            ->with($set->guest_forwarder_id, $game->guestTeam)
            ->andReturn(false);

        $gameManager = new GameManagerTestHelper($game, $this->teamService);
        $gameManager->setMode($mode);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Guest forwarder is not part of the guest team!");

        $gameManager->saveSet($set);
    }

    public function test_save_set_throws_exception_if_draw_is_attempted(): void
    {
        $set = Set::factory()->make([
            'home_goals'  => 2,
            'guest_goals' => 2,
        ]);

        $mode = $this->mock(BestOfMode::class);
        $mode->shouldReceive('isFinished')->andReturn(false);

        $game = Game::factory()->create(['mode' => 'BestOfThree', 'finished_at' => null]);

        $this->teamService->shouldReceive('isUserPartOf')
            ->with($set->home_forwarder_id, $game->homeTeam)
            ->andReturn(true);
        $this->teamService->shouldReceive('isUserPartOf')
            ->with($set->guest_forwarder_id, $game->guestTeam)
            ->andReturn(true);

        $gameManager = new GameManagerTestHelper($game, $this->teamService);
        $gameManager->setMode($mode);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Draw is not possible!');

        $gameManager->saveSet($set);
    }

    public function test_remove_set_removes_set_from_game(): void
    {
        $set = Set::factory()->create(['game_id' => $this->game->id]);

        $this->assertDatabaseHas('sets', $set->toArray());

        $this->gameManager->removeSet($set);

        $this->assertDatabaseMissing('sets', $set->toArray());
        $this->assertFalse($this->game->sets->contains($set));
    }

    public function test_remove_set_throws_exception_if_set_not_found(): void
    {
        $set = Set::factory()->make();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Set not found on this game');

        $this->gameManager->removeSet($set);
    }

    public function test_get_mode_returns_correct_instance(): void
    {
        $mode = $this->gameManager->getMode();

        $this->assertInstanceOf(BestOfMode::class, $mode);
    }

    public function test_update_game_statistics_sets_winner_and_finishes_game(): void
    {
        $mode = $this->mock(BestOfMode::class);
        $mode->shouldReceive('isFinished')->andReturn(true);
        $mode->shouldReceive('getWinner')->andReturn('home');

        $game = Game::factory()->create(['mode' => 'BestOfThree', 'finished_at' => null]);

        $gameManager = new GameManagerTestHelper($game, $this->teamService);
        $gameManager->setMode($mode);

        Event::fake([GameFinished::class]);

        $gameManager->publicUpdateGameStatistics();

        Event::assertDispatched(GameFinished::class);

        $this->assertNotNull($game->finished_at);
        $this->assertEquals('home', $game->winner);
    }

    public function test_load_returns_game_manager_instance(): void
    {
        $game = Game::factory()->create();

        $gameManager = GameManagerTestHelper::load($game->id, $this->teamService);

        $this->assertInstanceOf(GameManager::class, $gameManager);
        $this->assertEquals($game->id, $gameManager->game()->getKey());
    }
}

class GameManagerTestHelper extends GameManager
{
    protected $modetest;

    public function game()
    {
        return $this->game;
    }

    public function publicUpdateGameStatistics()
    {
        $this->updateGameStatistics();
    }

    public function setMode($mode)
    {
        $this->modetest = $mode;
    }

    public function getMode(): AbstractMode
    {
        return $this->modetest;
    }
}

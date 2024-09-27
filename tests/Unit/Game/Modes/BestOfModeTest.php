<?php

namespace Tests\Unit\Game\Modes;

use App\Game\Modes\BestOfMode;
use App\Models\Game;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;
use Tests\TestCase;

class BestOfModeTest extends TestCase
{
    private Game $game;

    protected function setUp(): void
    {
        parent::setUp();
        $this->game = new Game;
    }

    public function test_construct_creates_instance()
    {
        $mode = new BestOfMode(new Game, 5);
        $this->assertInstanceOf(BestOfMode::class, $mode);
    }

    public function test_construct_throws_exception_when_max_less_than_three()
    {
        $this->expectExceptionObject(new InvalidArgumentException('Minimum number of sets is three.'));

        new BestOfMode(new Game, 2);
    }

    public function test_construct_throws_exception_when_max_is_even()
    {
        $this->expectExceptionObject(new InvalidArgumentException('The maximum number of sets has to be odd.'));

        new BestOfMode(new Game, 4);
    }

    public function test_is_finished_returns_false_when_no_winner()
    {
        $mode = new BestOfMode($this->game, 5);
        $this->game->setRelation('sets', new Collection([]));
        $this->assertFalse($mode->isFinished());
    }

    public function test_is_finished_returns_true_when_home_team_wins()
    {
        $mode = new BestOfMode($this->game, 3);
        $this->game->setRelation('sets', new Collection([
            ['home_goals' => 1, 'guest_goals' => 0],
            ['home_goals' => 1, 'guest_goals' => 0],
        ]));
        $this->assertTrue($mode->isFinished());
    }

    public function test_is_finished_returns_true_when_guest_team_wins()
    {
        $mode = new BestOfMode($this->game, 3);
        $this->game->setRelation('sets', new Collection([
            ['home_goals' => 0, 'guest_goals' => 1],
            ['home_goals' => 0, 'guest_goals' => 1],
        ]));
        $this->assertTrue($mode->isFinished());
    }

    public function test_get_winner_returns_home_team()
    {
        $mode = new BestOfMode($this->game, 3);
        $this->game->setRelation('sets', new Collection([
            ['home_goals' => 1, 'guest_goals' => 0],
            ['home_goals' => 1, 'guest_goals' => 0],
            ['home_goals' => 0, 'guest_goals' => 1],
        ]));
        $this->assertEquals('home', $mode->getWinner());
    }

    public function test_get_winner_returns_guest_team()
    {
        $mode = new BestOfMode($this->game, 5);
        $this->game->setRelation('sets', new Collection([
            ['home_goals' => 0, 'guest_goals' => 1],
            ['home_goals' => 0, 'guest_goals' => 1],
            ['home_goals' => 1, 'guest_goals' => 0],
            ['home_goals' => 0, 'guest_goals' => 1],
            ['home_goals' => 0, 'guest_goals' => 1],
        ]));
        $this->assertEquals('guest', $mode->getWinner());
    }

    public function test_get_required_wins_returns_correct_value()
    {
        $mode = new BestOfMode($this->game, 5);
        $this->assertEquals(3, $mode->getRequiredWins());
    }
}

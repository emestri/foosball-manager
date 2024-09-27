<?php

namespace Tests\Unit\Game\Modes;

use App\Game\Modes\SingleMode;
use App\Models\Game;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class SingleModeTest extends TestCase
{
    private Game $game;

    protected function setUp(): void
    {
        parent::setUp();
        $this->game = new Game;
    }

    public function test_construct_creates_instance()
    {
        $mode = new SingleMode(new Game);
        $this->assertInstanceOf(SingleMode::class, $mode);
    }

    public function test_is_finished_returns_false_when_no_winner()
    {
        $mode = new SingleMode($this->game);
        $this->game->setRelation('sets', new Collection([]));
        $this->assertFalse($mode->isFinished());
    }

    public function test_is_finished_returns_true_when_home_team_wins()
    {
        $mode = new SingleMode($this->game);
        $this->game->setRelation('sets', new Collection([
            ['home_goals' => 1, 'guest_goals' => 0],
        ]));
        $this->assertTrue($mode->isFinished());
    }

    public function test_is_finished_returns_true_when_guest_team_wins()
    {
        $mode = new SingleMode($this->game);
        $this->game->setRelation('sets', new Collection([
            ['home_goals' => 0, 'guest_goals' => 1],
        ]));
        $this->assertTrue($mode->isFinished());
    }

    public function test_get_winner_returns_guest_team()
    {
        $mode = new SingleMode($this->game);
        $this->game->setRelation('sets', new Collection([
            ['home_goals' => 0, 'guest_goals' => 1],
        ]));
        $this->assertEquals('guest', $mode->getWinner());
    }

    public function test_get_winner_returns_home_team()
    {
        $mode = new SingleMode($this->game);
        $this->game->setRelation('sets', new Collection([
            ['home_goals' => 1, 'guest_goals' => 0],
        ]));
        $this->assertEquals('home', $mode->getWinner());
    }

    public function test_get_winner_returns_guest_team_even_if_more_sets_defined()
    {
        $mode = new SingleMode($this->game);
        $this->game->setRelation('sets', new Collection([
            ['home_goals' => 0, 'guest_goals' => 1],
            ['home_goals' => 1, 'guest_goals' => 0],
        ]));
        $this->assertEquals('guest', $mode->getWinner());
    }
}

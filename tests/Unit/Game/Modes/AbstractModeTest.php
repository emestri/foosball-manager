<?php

namespace Tests\Unit\Game\Modes;

use App\Models\Game;
use Illuminate\Database\Eloquent\Collection;
use App\Game\Modes\AbstractMode;
use Tests\TestCase;

class AbstractModeTest extends TestCase
{
    protected $mockGame;
    protected $concreteMode;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockGame = $this->mock(Game::class);
        $this->concreteMode = new class($this->mockGame) extends AbstractMode {
            public function isFinished(): bool
            {
                return true;
            }

            public function getWinner(): ?string
            {
                return self::HOME_TEAM;
            }

            public function getPubSets(): Collection
            {
                return self::getSets();
            }
        };
    }

    public function test_get_sets_returns_empty_collection_if_no_sets_relation()
    {
        $this->mockGame
            ->shouldReceive('getRelationValue')
            ->with('sets')
            ->andReturn(null);

        $sets = $this->concreteMode->getPubSets();

        $this->assertInstanceOf(Collection::class, $sets);
        $this->assertTrue($sets->isEmpty());
    }

    public function test_get_sets_returns_sets_collection_if_available()
    {
        $expectedSets = new Collection([['set1'], ['set2']]);

        $this->mockGame
            ->shouldReceive('getRelationValue')
            ->with('sets')
            ->andReturn($expectedSets);

        $sets = $this->concreteMode->getPubSets();

        $this->assertInstanceOf(Collection::class, $sets);
        $this->assertEquals($expectedSets, $sets);
    }
}

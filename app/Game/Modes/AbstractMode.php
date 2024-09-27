<?php

namespace App\Game\Modes;

use App\Models\Game;
use Illuminate\Database\Eloquent\Collection;

abstract class AbstractMode
{
    protected const HOME_TEAM = 'home';
    protected const GUEST_TEAM = 'guest';

    /**
     * Create a new game mode instance.
     *
     * @param  Game  $game
     */
    public function __construct(protected Game $game)
    {
    }

    /**
     * Determine if the game ist finished
     *
     * @return bool
     */
    abstract public function isFinished(): bool;

    /**
     * Determine the winner team.
     *
     * @return null|string
     */
    abstract public function getWinner(): ?string;

    /**
     * Get the played sets of the game.
     *
     * @return Collection
     */
    protected function getSets(): Collection
    {
        $sets = $this->game->getRelationValue('sets');

        return empty($sets) ? new Collection : $sets;
    }
}

<?php

namespace App\Game\Modes;

use App\Models\Game;
use InvalidArgumentException;

class BestOfMode extends AbstractMode
{
    /**
     * @inheritDoc
     */
    public function __construct(protected Game $game, protected int $max)
    {
        $this->validateMaxSets($max);

        parent::__construct($game);
    }

    /**
     * @inheritDoc
     */
    public function isFinished(): bool
    {
        return $this->getWinner() !== null;
    }

    /**
     * @inheritDoc
     */
    public function getWinner(): ?string
    {
        $requiredWins = $this->getRequiredWins();
        $wins = $this->calculateWins();

        if ($wins[self::HOME_TEAM] >= $requiredWins) {
            return self::HOME_TEAM;
        }

        if ($wins[self::GUEST_TEAM] >= $requiredWins) {
            return self::GUEST_TEAM;
        }

        return null;
    }

    /**
     * Get the required wins needed to win the game.
     *
     * @return int
     */
    public function getRequiredWins(): int
    {
        return (int) ($this->max / 2) + 1;
    }

    /**
     * Validate the maximum number of sets.
     *
     * @param int $max
     * @throws InvalidArgumentException
     */
    private function validateMaxSets(int $max): void
    {
        if ($max < 3) {
            throw new InvalidArgumentException('Minimum number of sets is three.');
        }

        if ($max % 2 === 0) {
            throw new InvalidArgumentException('The maximum number of sets has to be odd.');
        }
    }

    /**
     * Calculate the number of wins for each team based on played sets.
     *
     * @return array
     */
    private function calculateWins(): array
    {
        $wins = [self::HOME_TEAM => 0, self::GUEST_TEAM => 0];

        foreach ($this->getSets() as $set) {
            $wins[self::HOME_TEAM] += ($set['home_goals'] > $set['guest_goals']) ? 1 : 0;
            $wins[self::GUEST_TEAM] += ($set['home_goals'] < $set['guest_goals']) ? 1 : 0;
        }

        return $wins;
    }
}

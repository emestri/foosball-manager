<?php

namespace App\Game\Modes;

class SingleMode extends AbstractMode
{
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
        $set = $this->getSets()->first();

        if (empty($set)) {
            return null;
        }

        return $set['home_goals'] > $set['guest_goals'] ? self::HOME_TEAM : self::GUEST_TEAM;
    }
}

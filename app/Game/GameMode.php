<?php

namespace App\Game;

use JsonSerializable;

enum GameMode implements JsonSerializable
{
    case Single;
    case BestOfThree;
    case BestOfFive;

    public function jsonSerialize(): mixed
    {
        return $this->name;
    }

    /**
     * Get the enum by name.
     *
     * @param  string  $name
     * @return GameMode|null
     */
    public static function from(string $name): ?GameMode
    {
        foreach (GameMode::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        return null;
    }
}

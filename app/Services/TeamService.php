<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use InvalidArgumentException;

class TeamService
{
    /**
     * Create o
     *
     * @param  int|User  $playerOne
     * @param  int|User  $playerTwo
     * @return Team
     */
    public function create(int|User $playerOne, int|User $playerTwo): Team
    {
        $team = [
            resolveModel(User::class, $playerOne),
            resolveModel(User::class, $playerTwo),
        ];

        if ($team[0]->getKey() === $team[1]->getKey()) {
            throw new InvalidArgumentException('Teams with same players are not allowed');
        }

        // Sort to have the right order and prevent duplicate teams.
        usort($team, function ($a, $b) {
            return $a->getKey() <=> $b->getKey();
        });

        return Team::firstOrCreate([
            'player_one_id' => $team[0]->getKey(),
            'player_two_id' => $team[1]->getKey(),
        ]);
    }

    /**
     * Determine if the user is part of the given team.
     *
     * @param  int|User  $user
     * @param  Team  $team
     * @return bool
     */
    public function isUserPartOf(int|User $user, Team $team): bool
    {
        $key = $user instanceof User ? $user->getKey() : $user;

        if ($team->playerOne->getKey() === $key) {
            return true;
        }

        if ($team->playerTwo->getKey() === $key) {
            return true;
        }

        return false;
    }
}

<?php

namespace App\Game;

use App\Models\Game;
use App\Models\Location;
use App\Models\Team;
use App\Services\TeamService;

class GameFactory
{
    /**
     * Create a single mode game.
     *
     * @param  Team  $home
     * @param  Team  $guest
     * @param  Location  $location
     * @return GameManager
     */
    public static function single(Team $home, Team $guest, Location $location): GameManager
    {
        return self::create($home, $guest, $location, GameMode::Single);
    }

    /**
     * Create a best of three mode game.
     *
     * @param  Team  $home
     * @param  Team  $guest
     * @param  Location  $location
     * @return GameManager
     */
    public static function bestOfThree(Team $home, Team $guest, Location $location): GameManager
    {
        return self::create($home, $guest, $location, GameMode::BestOfThree);
    }

    /**
     * Create a best of three mode game.
     *
     * @param  Team  $home
     * @param  Team  $guest
     * @param  Location  $location
     * @return GameManager
     */
    public static function bestOfFive(Team $home, Team $guest, Location $location): GameManager
    {
        return self::create($home, $guest, $location, GameMode::BestOfFive);
    }

    /**
     * Create a new game.
     *
     * @param  Team  $home
     * @param  Team  $guest
     * @param  Location  $location
     * @param  GameMode  $mode
     * @return GameManager
     */
    public static function create(
        Team $home,
        Team $guest,
        Location $location,
        GameMode $mode
    ): GameManager {
        $game = Game::create([
            'home_team_id' => $home->getKey(),
            'guest_team_id' => $guest->getKey(),
            'location_id' => $location->getKey(),
            'mode' => $mode->name,
            'kickoff_at' => now(),
        ])->load([
            'sets',
            'homeTeam' => ['playerOne', 'playerTwo'],
            'guestTeam' => ['playerOne', 'playerTwo']
        ]);

        return new GameManager($game, new TeamService);
    }
}

<?php

namespace App\Game;

use App\Events\GameFinished;
use App\Game\Modes\AbstractMode;
use App\Game\Modes\BestOfMode;
use App\Game\Modes\SingleMode;
use App\Models\Game;
use App\Models\Set;
use App\Services\TeamService;
use LogicException;

/**
 * @todo forward to model and remove game model access
 */
class GameManager
{
    /**
     * The game mode instance.
     *
     * @var ?AbstractMode
     */
    protected ?AbstractMode $mode = null;

    /**
     * Create a new game handler service instance.
     *
     * @param  Game  $game
     * @param  TeamService  $teamService
     */
    public function __construct(protected Game $game, protected TeamService $teamService)
    {
    }

    /**
     * Add or update a set to the game.
     *
     * @param  Set  $set
     * @return void
     */
    public function saveSet(Set $set): void
    {
        $this->validateSet($set);
        $this->game->sets()->save($set);
        $this->refreshSets();
        $this->updateGameStatistics();
    }

    /**
     * Remove a set from the game.
     *
     * @param  Set  $set
     * @return void
     */
    public function removeSet(Set $set): void
    {
        if (! $this->game->sets->contains($set)) {
            throw new LogicException('Set not found on this game');
        }

        $set->delete();
        $this->refreshSets();

        $this->updateGameStatistics();
    }

    /**
     * Get the game mode instance.
     *
     * @return AbstractMode
     */
    public function getMode(): AbstractMode
    {
        return $this->mode ??= match ($this->game->mode) {
            GameMode::BestOfFive => new BestOfMode($this->game, 5),
            GameMode::BestOfThree => new BestOfMode($this->game, 3),
            GameMode::Single => new SingleMode($this->game),
        };
    }

    /**
     * Get the game model instance.
     *
     * @return Game
     */
    public function getGame(): Game
    {
        return $this->game;
    }

    /**
     * Validate the set before adding it.
     *
     * @param  Set  $set
     * @return void
     */
    protected function validateSet(Set $set): void
    {
        if (! $set->exists && $this->getMode()->isFinished()) {
            throw new LogicException('Cannot add a new set, the game it is already finished.');
        }

        if (! $this->teamService->isUserPartOf($set->home_forwarder_id, $this->game->homeTeam)) {
            throw new LogicException("Home forwarder is not part of the home team!");
        }

        if (! $this->teamService->isUserPartOf($set->guest_forwarder_id, $this->game->guestTeam)) {
            throw new LogicException("Guest forwarder is not part of the guest team!");
        }

        if ($set->home_goals === $set->guest_goals) {
            throw new LogicException('Draw is not possible!');
        }
    }

    /**
     * Update game statistics.
     *
     * @return void
     */
    protected function updateGameStatistics(): void
    {
        $isFinished = $this->getMode()->isFinished();
        $attributes = [
            'winner' => $this->getMode()->getWinner(),
        ];

        if ($isFinished && ! $this->game['finished_at']) {
            $attributes['finished_at'] = now();
            event(new GameFinished($this->game));
        } else {
            $attributes['finished_at'] = null;
        }

        $this->game->update($attributes);
    }

    /**
     * Refresh the relation data of sets.
     *
     * @return void
     */
    protected function refreshSets(): void
    {
        $this->game->load('sets');
    }

    /**
     * Load a game by its id.
     *
     * @param  int  $id
     * @param  null|TeamService  $teamService
     * @return static
     */
    public static function load(int $id, ?TeamService $teamService = null): static
    {
        $game = Game::with([
            'sets',
            'homeTeam'  => ['playerOne', 'playerTwo'],
            'guestTeam' => ['playerOne', 'playerTwo'],
        ])->findOrFail($id);

        return new static($game, $teamService ?: new TeamService);
    }
}

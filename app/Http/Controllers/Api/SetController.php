<?php

namespace App\Http\Controllers\Api;

use App\Game\GameManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\Set\StoreRequest;
use App\Http\Resources\SetResource;
use App\Models\Game;
use App\Models\Set;
use Illuminate\Http\JsonResponse;

class SetController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, Game $game, Set $set): SetResource
    {
        return $this->save($request, $set, $game->getKey());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, Set $set): SetResource
    {
        return $this->save($request, $set, $set->game_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Game  $game
     * @param  Set  $set
     * @return JsonResponse
     */
    public function destroy(Game $game, Set $set): JsonResponse
    {
        $manager = $this->getGameManager($game->getKey());
        $manager->removeSet($set);

        return response()->json([
            'message' => 'Set deleted successfully',
        ]);
    }

    /**
     * Save a set of a game.
     *
     * @param  Set $set
     * @param  StoreRequest  $request
     * @param  int  $gameId
     * @return SetResource
     */
    protected function save(StoreRequest $request, Set $set, int $gameId): SetResource
    {
        $set->fill($request->validated());

        $manager = $this->getGameManager($gameId);
        $manager->saveSet($set);

        return SetResource::make($set->load('homeForwarder', 'guestForwarder'));
    }

    /**
     * Get the game manager instance.
     *
     * @param  int  $gameId
     * @return GameManager
     */
    protected function getGameManager(int $gameId): GameManager
    {
        return GameManager::load($gameId);
    }
}

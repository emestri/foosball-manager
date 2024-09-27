<?php

namespace App\Http\Controllers\Api;

use App\Game\GameFactory;
use App\Game\GameMode;
use App\Http\Controllers\Controller;
use App\Http\Requests\Game\CreateRequest;
use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Models\Location;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return GameResource::collection(Game::with([
            'location',
            'sets',
            'homeTeam',
            'guestTeam',
        ])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request, GameFactory $factory): GameResource
    {
        $home = Team::findOrFail($request->get('home_team_id'));
        $guest = Team::findOrFail($request->get('guest_team_id'));
        $location = Location::findOrFail($request->get('location_id'));
        $mode = GameMode::from($request->get('mode'));

        return GameResource::make(GameFactory::create($home, $guest, $location, $mode)->getGame());
    }

    /**
     * Display the specified resource.
     *
     * @param  Game  $game
     * @return GameResource
     */
    public function show(Game $game): GameResource
    {
        return GameResource::make($game->load([
            'location',
            'sets',
            'homeTeam' => [
                'playerOne',
                'playerTwo',
            ],
            'guestTeam' => [
                'playerOne',
                'playerTwo',
            ],
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        // @todo
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Game  $game
     * @return JsonResponse
     */
    public function destroy(Game $game): JsonResponse
    {
        $game->delete();

        return response()->json([
            'message' => 'Set deleted successfully',
        ]);
    }
}

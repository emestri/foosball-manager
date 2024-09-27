<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TeamResource::collection(Team::with('playerOne', 'playerTwo')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // @todo
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        return TeamResource::make($team->load('playerOne', 'playerTwo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        // @todo
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        // @todo
    }
}

<?php

use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\SetController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/token', [UserController::class, 'token'])->name('users.token');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResources([
        'users' => UserController::class,
        'teams' => TeamController::class,
        'games' => GameController::class,
    ]);

    Route::apiResource('games.sets', SetController::class)->only('destroy');

    Route::apiResource('games.sets', SetController::class)
        ->except(['index', 'show', 'destroy'])
        ->shallow();
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

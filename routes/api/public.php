<?php

use App\Http\Controllers\ContestantController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

/*
 * --------------------------------
 * Contestants
 * --------------------------------
 */
Route::prefix('contestants')
    ->controller(ContestantController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{contestant}', 'show')->where('contestant', '[0-9]+');
    });

/*
 * --------------------------------
 * Tournaments
 * --------------------------------
 */
Route::prefix('tournaments')
    ->controller(TournamentController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{tournament}', 'show')->where('tournament', '[0-9]+');
    });

/*
 * --------------------------------
 * Games
 * --------------------------------
 */
Route::prefix('games')
    ->controller(GameController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{game}', 'show')->where('game', '[0-9]+');
    });

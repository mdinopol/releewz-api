<?php

use App\Http\Controllers\ContestantController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MattchController;
use App\Http\Controllers\ScoreController;
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
        Route::get('/state/{gameState}/sport/{sportName?}', 'index');
        Route::get('/i/{game:id}', 'show');
        Route::get('/s/{game:slug}', 'show');
    });

/*
 * --------------------------------
 * Scores
 * --------------------------------
 */
Route::prefix('scores')
    ->controller(ScoreController::class)
    ->group(function () {
        Route::get('/{score}', 'show')->where('score', '[0-9]+');
    });

/*
 * --------------------------------
 * Mattches
 * --------------------------------
 */
Route::prefix('mattches')
    ->controller(MattchController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{mattch}', 'show')->where('mattch', '[0-9]+');
    });

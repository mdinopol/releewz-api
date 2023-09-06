<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\ContestantController;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

/*
 * --------------------------------
 * Achievements
 * --------------------------------
 */
Route::prefix('achievements')
    ->controller(AchievementController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{achievement}', 'show')->where('achievement', '[0-9]+');
    });

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

/**
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
<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\ContestantController;
use Illuminate\Support\Facades\Route;

/*
 * Achievements
 */
Route::prefix('achievements')
    ->controller(AchievementController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{achievement}', 'show')->where('achievement', '[0-9]+');
    });

/**
 * Contestants
 */
Route::prefix('contestants')
    ->controller(ContestantController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{contestant}', 'show')->where('contestant', '[0-9]+');
    });

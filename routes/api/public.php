<?php

use App\Http\Controllers\AchievementController;
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

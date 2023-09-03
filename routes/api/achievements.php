<?php

use App\Enum\Role;
use App\Http\Controllers\AchievementController;
use Illuminate\Support\Facades\Route;

Route::controller(AchievementController::class)->group(function () {
    Route::middleware([rr(Role::ADMIN)])->group(function () {
        Route::post('/', 'store');
        Route::put('/{achievement}', 'update')->where('achievement', '[0-9]+');
        Route::delete('/{achievement}', 'destroy')->where('achievement', '[0-9]+');
    });
});

<?php

use App\Enum\Role;
use App\Http\Controllers\ScoreController;
use Illuminate\Support\Facades\Route;

Route::controller(ScoreController::class)->group(function () {
    Route::middleware([rr(Role::ADMIN)])->group(function () {
        Route::post('/', 'store');
        Route::put('/{score}', 'update')->where('score', '[0-9]+');
    });
});

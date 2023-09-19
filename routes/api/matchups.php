<?php

use App\Enum\Role;
use App\Http\Controllers\MatchupController;
use Illuminate\Support\Facades\Route;

Route::controller(MatchupController::class)->group(function () {
    Route::middleware([rr(Role::ADMIN)])->group(function () {
        Route::post('/', 'store');
        Route::put('/{matchup}', 'update')->where('matchup', '[0-9]+');
        Route::delete('/{matchup}', 'destroy')->where('matchup', '[0-9]+');
    });
});

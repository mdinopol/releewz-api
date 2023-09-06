<?php

use App\Enum\Role;
use App\Http\Controllers\TournamentController;
use Illuminate\Support\Facades\Route;

Route::controller(TournamentController::class)->group(function () {
    Route::middleware([rr(Role::ADMIN)])->group(function () {
        Route::post('/', 'store');
        Route::put('/{tournament}', 'update')->where('tournament', '[0-9]+');
        Route::delete('/{tournament}', 'destroy')->where('tournament', '[0-9]+');
    });
});

<?php

use App\Enum\Role;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::controller(GameController::class)->group(function () {
    Route::middleware([rr(Role::ADMIN)])->group(function () {
        // Route::get('/myGames', 'mine');
        Route::post('/', 'store');
        Route::put('/{game}', 'update')->where('game', '[0-9]+');
        Route::delete('/{game}', 'destroy')->where('game', '[0-9]+');

        Route::put('/{game}/state/{gameState}', 'updateGameState');
        Route::post('/{game}/startlist', 'syncStartlist');
        // Route::delete('/{game}/startlist', 'syncStartlist')->where('game', '[0-9]+');
    });

    Route::prefix('{game}/entries')->group(function () {
        Route::post('/', 'createUserEntry');
        Route::get('/', 'myEntries');
    });
});

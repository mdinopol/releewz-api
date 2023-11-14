<?php

use App\Enum\Role;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::controller(GameController::class)->group(function () {
    Route::middleware([rr(Role::ADMIN)])->group(function () {
        // Route::get('/myGames', 'mine');
        Route::post('/', 'store');

        Route::prefix('{game}')->group(function () {
            Route::put('', 'update')->where('game', '[0-9]+');
            Route::delete('', 'destroy')->where('game', '[0-9]+');

            Route::put('/state/{gameState}', 'updateGameState');
            Route::post('/startlist', 'syncStartlist');
            Route::post('/point-template', 'setPointTemplate');
        });
    });

    Route::get('/entries/state/{gameState}', 'myEntries');

    Route::prefix('{game}/entries')->group(function () {
        Route::post('/', 'createUserEntry');
    });
});

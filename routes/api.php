<?php

use Illuminate\Support\Facades\Route;

Route::middleware([
    'throttle:60,1',
])->group(function () {
    // Health check
    Route::get('/health_check', static function () {
        return response()->json(['status' => 'ok']);
    });

    Route::prefix('oauth')
        ->group(base_path('routes/api/oauth.php'));

    // Public routes
    Route::prefix('')
        ->group(base_path('routes/api/public.php'));
});

Route::middleware([
    'auth:api',
])->group(function () {
    Route::prefix('users')
        ->group(base_path('routes/api/users.php'));

    Route::prefix('contestants')
        ->group(base_path('routes/api/contestants.php'));

    Route::prefix('tournaments')
        ->group(base_path('routes/api/tournaments.php'));

    Route::prefix('games')
        ->group(base_path('routes/api/games.php'));

    Route::prefix('scores')
        ->group(base_path('routes/api/scores.php'));

    Route::prefix('mattches')
        ->group(base_path('routes/api/mattches.php'));
});

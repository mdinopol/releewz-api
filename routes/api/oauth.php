<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;

Route::post('/register', [UserController::class, 'register']);

Route::controller(AccessTokenController::class)->group(function () {
    Route::middleware([
        'throttle:10,1',
        'role.login',
    ])->group(function () {
        Route::post('/token', 'issueToken');
    });

    Route::middleware([
        'auth:api',
    ])->group(function () {
        Route::post('/token/refresh', 'refresh');
    });
});

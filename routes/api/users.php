<?php

use App\Enum\Role;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function () {
    // Superadmin
    Route::middleware([rr(Role::SUPER_ADMIN)])->group(function () {
        Route::post('/', 'store');
        Route::put('/{user}', 'update')->where('user', '[0-9]+');
        Route::delete('/{user}', 'destroy');
    });

    // Admin
    Route::middleware([rr(Role::ADMIN)])->group(function () {
        Route::get('/', 'index');
        Route::get('/{user}', 'show')->where('user', '[0-9]+');
    });

    // Normal client
    Route::middleware([rr(Role::USER)])->group(function () {
        Route::get('/me', 'me');
        Route::put('/me', 'updateMe');
    });

    Route::post('/logout', 'logout');
});

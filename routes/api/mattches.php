<?php

use App\Enum\Role;
use App\Http\Controllers\MattchController;
use Illuminate\Support\Facades\Route;

Route::controller(MattchController::class)->group(function () {
    Route::middleware([rr(Role::ADMIN)])->group(function () {
        Route::post('/', 'store');
        Route::put('/{mattch}', 'update')->where('mattch', '[0-9]+');
        Route::delete('/{mattch}', 'destroy')->where('mattch', '[0-9]+');
    });
});

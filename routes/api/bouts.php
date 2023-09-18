<?php

use App\Enum\Role;
use App\Http\Controllers\BoutController;
use Illuminate\Support\Facades\Route;

Route::controller(BoutController::class)->group(function () {
    Route::middleware([rr(Role::ADMIN)])->group(function () {
        Route::post('/', 'store');
        Route::put('/{bout}', 'update')->where('bout', '[0-9]+');
        Route::delete('/{bout}', 'destroy')->where('bout', '[0-9]+');
    });
});

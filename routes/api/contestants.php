<?php

use App\Enum\Role;
use App\Http\Controllers\ContestantController;
use Illuminate\Support\Facades\Route;

Route::controller(ContestantController::class)->group(function () {
    Route::middleware([rr(Role::ADMIN)])->group(function () {
        Route::post('/', 'store');
        Route::put('/{contestant}', 'update')->where('contestant', '[0-9]+');
        Route::delete('/{contestant}', 'destroy')->where('contestant', '[0-9]+');
    });
});

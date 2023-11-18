<?php

use App\Enum\Role;
use App\Http\Controllers\EventtController;
use Illuminate\Support\Facades\Route;

Route::controller(EventtController::class)->group(function () {
    Route::middleware([rr(Role::ADMIN)])->group(function () {
        Route::get('', 'list');
        Route::post('', 'store');
        Route::get('/{eventt}', 'show')->where('eventt', '[0-9]+');
        Route::put('/{eventt}', 'update')->where('eventt', '[0-9]+');
        Route::delete('/{eventt}', 'destroy')->where('eventt', '[0-9]+');
    });
});

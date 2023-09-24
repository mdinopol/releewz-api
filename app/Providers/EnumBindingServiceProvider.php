<?php

namespace App\Providers;

use App\Enum\GameState;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class EnumBindingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::bind('gameState', function ($value) {
            return GameState::tryFrom($value);
        });
    }
}

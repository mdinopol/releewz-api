<?php

namespace App\Providers;

use App\Listeners\RevokeUserExistingToken;
use App\Models\Game;
use App\Models\Score;
use App\Observers\GameObserver;
use App\Observers\ScoreObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Laravel\Passport\Events\AccessTokenCreated;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        AccessTokenCreated::class => [
            RevokeUserExistingToken::class,
        ],
    ];

    /**
     * The model observers for your application.
     *
     * @var array<string, array<int, string>>
     */
    protected $observers = [
        Game::class  => [GameObserver::class],
        Score::class => [ScoreObserver::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}

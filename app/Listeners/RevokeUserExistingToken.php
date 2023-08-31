<?php

namespace App\Listeners;

use App\Models\User;
use Laravel\Passport\Events\AccessTokenCreated;

/**
 * Revokes user's existing access token when new access token is generated.
 */
class RevokeUserExistingToken
{
    public function handle(AccessTokenCreated $event): void
    {
        /**
         * @var User|null
         */
        $user = User::find($event->userId);

        if ($user) {
            $user->tokens()->offset(1)->limit(PHP_INT_MAX)->get()->map(function ($token) {
                $token->revoke();
            });
        }
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class OauthClientRoleAuthorizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param \Closure $next
     *
     * @return Response|RedirectResponse|JsonResponse
     */
    public function handle(Request $request, \Closure $next): Response|RedirectResponse|JsonResponse
    {
        $authorized = true;

        // Get client type based on request
        $client = DB::table('oauth_clients')
            ->select(['minimum_role', 'for_role'])
            ->where('id', $request->get('client_id'))
            ->first();

        // Check if client type exist
        if ($client) {
            /**
             * @var User|null
             */
            $user = User::where('email', $request->get('username'))->first();

            if ($user) {
                // Exact role comparison
                if ($client->for_role) {
                    $authorized = $user->role->value === $client->minimum_role;
                }

                // Minimum role comparison
                if ($client->minimum_role) {
                    $authorized = $user->hasRoleAuthorization($client->minimum_role);
                }
            }
        }

        if (!$authorized) {
            abort(HttpResponse::HTTP_UNAUTHORIZED, 'Unauthorized.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class RoleAuthorizationMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, \Closure $next, string $requiredRoleForAction): Response|RedirectResponse|JsonResponse
    {
        /*
         * @var \App\Models\User|null
         */
        if (!$user = $request->user()) {
            abort(HttpResponse::HTTP_UNAUTHORIZED, 'Forbidden');
        }

        if (!$user->hasRoleAuthorization($requiredRoleForAction)) {
            abort(HttpResponse::HTTP_UNAUTHORIZED, 'Forbidden');
        }

        return $next($request);
    }
}

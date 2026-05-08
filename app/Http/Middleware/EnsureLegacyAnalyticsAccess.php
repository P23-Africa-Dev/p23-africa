<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLegacyAnalyticsAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Forbidden');
        }

        $isAllowedRole = $user->hasRole('admin') || $user->hasRole('supervisor');
        $isLegacyContext = is_null($user->organization_id);

        if (!$isAllowedRole || !$isLegacyContext) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}

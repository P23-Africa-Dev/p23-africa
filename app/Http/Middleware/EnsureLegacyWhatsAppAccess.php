<?php

namespace App\Http\Middleware;

use App\Services\AccessAuditService;
use App\Services\LegacyWhatsAppAccessService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLegacyWhatsAppAccess
{
    public function __construct(
        protected LegacyWhatsAppAccessService $legacyWhatsAppAccessService,
        protected AccessAuditService $accessAuditService,
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($this->legacyWhatsAppAccessService->canAccessWhatsApp($user)) {
            return $next($request);
        }

        $this->accessAuditService->logDeniedWhatsAppAccess(
            $request,
            $user,
            'denied',
            'Legacy WhatsApp access requires organization_id = null and role admin|supervisor.'
        );

        abort(403, 'Forbidden');
    }
}

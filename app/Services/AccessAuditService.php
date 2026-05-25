<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccessAuditService
{
    /**
     * Log denied WhatsApp access attempts with security-relevant request context.
     */
    public function logDeniedWhatsAppAccess(?Request $request, ?User $user, string $status = 'denied', ?string $reason = null): void
    {
        Log::warning('WhatsApp access denied', [
            'module' => 'whatsapp',
            'user_id' => $user?->id,
            'organization_id' => $user?->organization_id,
            'role_names' => $user ? $user->getRoleNames()->values()->all() : [],
            'route' => $request?->route()?->getName() ?? $request?->path(),
            'method' => $request?->method(),
            'ip' => $request?->ip(),
            'status' => $status,
            'reason' => $reason,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}

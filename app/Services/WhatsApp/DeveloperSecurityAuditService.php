<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsappSecurityLog;
use Illuminate\Http\Request;

class DeveloperSecurityAuditService
{
    public function log(Request $request, string $action, string $status): void
    {
        WhatsappSecurityLog::query()->create([
            'user_id' => $request->user()?->id,
            'action' => $action,
            'ip' => $request->ip(),
            'status' => $status,
        ]);
    }
}

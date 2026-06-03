<?php

namespace App\Policies;

use App\Models\User;
use App\Services\LegacyContextService;

class WhatsAppPolicy
{
    public function __construct(protected LegacyContextService $legacyContextService) {}

    public function view(User $user): bool
    {
        return $this->legacyContextService->hasLegacyWhatsAppAccess($user);
    }

    public function send(User $user): bool
    {
        return $this->legacyContextService->hasLegacyWhatsAppAccess($user);
    }

    public function manage(User $user): bool
    {
        return $this->legacyContextService->hasLegacyWhatsAppAccess($user);
    }
}

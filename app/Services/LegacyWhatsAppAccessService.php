<?php

namespace App\Services;

use App\Models\User;

class LegacyWhatsAppAccessService
{
    public function __construct(protected LegacyContextService $legacyContextService) {}

    /**
     * Legacy context is defined as users without an organization assignment.
     */
    public function isLegacyOrganization(?User $user): bool
    {
        return $this->legacyContextService->isLegacyUser($user);
    }

    public function isAllowedRole(?User $user): bool
    {
        return $this->legacyContextService->isLegacyAdmin($user)
            || $this->legacyContextService->isLegacySupervisor($user);
    }

    public function canAccessWhatsApp(?User $user): bool
    {
        return $this->legacyContextService->hasLegacyWhatsAppAccess($user);
    }
}

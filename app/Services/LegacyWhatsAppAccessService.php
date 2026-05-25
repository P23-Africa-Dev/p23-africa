<?php

namespace App\Services;

use App\Models\User;

class LegacyWhatsAppAccessService
{
    /**
     * Legacy context is defined as users without an organization assignment.
     */
    public function isLegacyOrganization(?User $user): bool
    {
        return (bool) $user && is_null($user->organization_id);
    }

    public function isAllowedRole(?User $user): bool
    {
        return (bool) $user && $user->hasRole(['admin', 'supervisor']);
    }

    public function canAccessWhatsApp(?User $user): bool
    {
        return $this->isLegacyOrganization($user) && $this->isAllowedRole($user);
    }
}

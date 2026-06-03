<?php

namespace App\Services;

use App\Models\User;

class LegacyContextService
{
    public function isLegacyUser(?User $user): bool
    {
        return (bool) $user && is_null($user->organization_id);
    }

    public function isLegacyAdmin(?User $user): bool
    {
        return $this->isLegacyUser($user) && $user->hasRole('admin');
    }

    public function isLegacySupervisor(?User $user): bool
    {
        return $this->isLegacyUser($user) && $user->hasRole('supervisor');
    }

    public function hasLegacyWhatsAppAccess(?User $user): bool
    {
        return $this->isLegacyAdmin($user) || $this->isLegacySupervisor($user);
    }
}
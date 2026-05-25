<?php

namespace App\Policies;

use App\Models\User;
use App\Services\LegacyWhatsAppAccessService;

class WhatsAppPolicy
{
    public function __construct(protected LegacyWhatsAppAccessService $legacyWhatsAppAccessService) {}

    public function view(User $user): bool
    {
        return $this->legacyWhatsAppAccessService->canAccessWhatsApp($user);
    }

    public function send(User $user): bool
    {
        return $this->legacyWhatsAppAccessService->canAccessWhatsApp($user);
    }

    public function manage(User $user): bool
    {
        return $this->legacyWhatsAppAccessService->canAccessWhatsApp($user);
    }
}

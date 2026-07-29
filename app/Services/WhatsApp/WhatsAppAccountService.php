<?php

namespace App\Services\WhatsApp;

use App\Models\User;
use App\Models\WhatsAppAccount;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class WhatsAppAccountService
{
    /**
     * Resolve the WhatsApp account the given user should currently act on.
     *
     * Prefers the user's explicitly selected account. If none, or if the selected
     * account is no longer active/available, falls back to the oldest active
     * account so the dashboard still has a working sender.
     */
    public function getActiveAccountForUser(?User $user): ?WhatsAppAccount
    {
        if ($user && $user->whatsapp_account_id) {
            $account = WhatsAppAccount::query()
                ->where('id', $user->whatsapp_account_id)
                ->first();

            if ($account && $account->status === 'active') {
                return $account;
            }
        }

        return WhatsAppAccount::active()->oldest('id')->first();
    }

    /**
     * Persist the user's chosen active WhatsApp account.
     */
    public function switchAccount(User $user, int $accountId): WhatsAppAccount
    {
        $account = WhatsAppAccount::query()
            ->where('id', $accountId)
            ->first();

        if (!$account) {
            throw new InvalidArgumentException('WhatsApp account not found.');
        }

        if ($account->status !== 'active') {
            throw new InvalidArgumentException('WhatsApp account is not active.');
        }

        $user->forceFill(['whatsapp_account_id' => $account->id])->save();

        return $account;
    }

    /**
     * All accounts marked as active, ordered oldest first for stable UI order.
     *
     * @return Collection<int, WhatsAppAccount>
     */
    public function allActiveAccounts(): Collection
    {
        return WhatsAppAccount::active()->oldest('id')->get();
    }

    /**
     * Every configured account (active or otherwise) for admin listings.
     *
     * @return Collection<int, WhatsAppAccount>
     */
    public function allAccounts(): Collection
    {
        return WhatsAppAccount::query()->oldest('id')->get();
    }
}

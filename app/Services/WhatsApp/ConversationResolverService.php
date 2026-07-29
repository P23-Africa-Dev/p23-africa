<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppContact;
use App\Models\WhatsAppConversation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ConversationResolverService
{
    public function findExistingConversation(
        ?string $phone = null,
        ?string $waId = null,
        ?int $contactId = null,
        ?int $accountId = null,
    ): ?WhatsAppConversation {
        if ($waId && ($conversation = $this->matchByWaId($waId, $accountId))) {
            return $conversation;
        }

        if ($phone && ($conversation = $this->matchByPhone($phone, $accountId))) {
            return $conversation;
        }

        if ($contactId && ($conversation = $this->matchByContact($contactId, $accountId))) {
            return $conversation;
        }

        return null;
    }

    public function resolveConversation(
        ?string $phone = null,
        ?string $waId = null,
        ?int $contactId = null,
        ?string $name = null,
        array $contactAttributes = [],
        array $conversationAttributes = [],
        ?int $accountId = null,
    ): WhatsAppConversation {
        $contact = $this->resolveContact($phone, $waId, $contactId, $name, $contactAttributes);

        $conversation = $this->findExistingConversation(
            $phone,
            $waId,
            $contact?->id,
            $accountId,
        );

        if ($conversation) {
            return $this->syncConversationIdentity($conversation, $contact, $phone, $waId, $conversationAttributes, $accountId);
        }

        return $this->createIfMissing($phone, $waId, $contact?->id, $name, $contactAttributes, $conversationAttributes, $accountId);
    }

    public function matchByWaId(string $waId, ?int $accountId = null): ?WhatsAppConversation
    {
        $sanitizedWaId = WhatsAppConversation::sanitizeWaId($waId);
        if (!$sanitizedWaId) {
            return null;
        }

        return WhatsAppConversation::query()
            ->with(['contact', 'assignedUser'])
            ->when($accountId !== null, fn (Builder $q) => $q->where('account_id', $accountId))
            ->where('wa_id', $sanitizedWaId)
            ->orderByDesc('updated_at')
            ->first();
    }

    public function matchByPhone(string $phone, ?int $accountId = null): ?WhatsAppConversation
    {
        $candidates = WhatsAppConversation::phoneCandidates($phone);
        if ($candidates === []) {
            return null;
        }

        return WhatsAppConversation::query()
            ->with(['contact', 'assignedUser'])
            ->when($accountId !== null, fn (Builder $q) => $q->where('account_id', $accountId))
            ->where(function ($query) use ($candidates) {
                $query->whereIn('normalized_phone', $candidates)
                    ->orWhereHas('contact', function ($contactQuery) use ($candidates) {
                        $contactQuery->whereIn('phone', $candidates);
                    });
            })
            ->orderByDesc('updated_at')
            ->first();
    }

    public function matchByContact(int $contactId, ?int $accountId = null): ?WhatsAppConversation
    {
        return WhatsAppConversation::query()
            ->with(['contact', 'assignedUser'])
            ->when($accountId !== null, fn (Builder $q) => $q->where('account_id', $accountId))
            ->where('contact_id', $contactId)
            ->orderByDesc('updated_at')
            ->first();
    }

    public function createIfMissing(
        ?string $phone = null,
        ?string $waId = null,
        ?int $contactId = null,
        ?string $name = null,
        array $contactAttributes = [],
        array $conversationAttributes = [],
        ?int $accountId = null,
    ): WhatsAppConversation {
        $contact = $this->resolveContact($phone, $waId, $contactId, $name, $contactAttributes);
        $normalizedPhone = WhatsAppConversation::normalizePhone($phone ?: $contact?->phone);
        $sanitizedWaId = WhatsAppConversation::sanitizeWaId($waId);
        $conversationKey = WhatsAppConversation::buildConversationKey($sanitizedWaId, $normalizedPhone, $contact?->id, $accountId);

        return DB::transaction(function () use ($contact, $sanitizedWaId, $normalizedPhone, $conversationKey, $conversationAttributes, $accountId) {
            $conversation = WhatsAppConversation::query()->firstOrCreate(
                ['conversation_key' => $conversationKey],
                array_merge([
                    'account_id' => $accountId,
                    'contact_id' => $contact->id,
                    'wa_id' => $sanitizedWaId,
                    'normalized_phone' => $normalizedPhone,
                    'status' => WhatsAppConversation::STATUS_OPEN,
                    'unread_count' => 0,
                ], $conversationAttributes)
            );

            return $this->syncConversationIdentity($conversation, $contact, $normalizedPhone, $sanitizedWaId, $conversationAttributes, $accountId);
        });
    }

    protected function resolveContact(
        ?string $phone,
        ?string $waId,
        ?int $contactId,
        ?string $name,
        array $contactAttributes,
    ): WhatsAppContact {
        $contact = $contactId ? WhatsAppContact::query()->find($contactId) : null;

        if (!$contact && $phone) {
            $candidates = WhatsAppConversation::phoneCandidates($phone);

            $contact = WhatsAppContact::query()
                ->whereIn('phone', $candidates)
                ->first();
        }

        if (!$contact) {
            $contact = new WhatsAppContact();
            $contact->phone = WhatsAppConversation::normalizePhone($phone) ?: trim((string) $phone);
            $contact->unsaved_contact = (bool) ($contactAttributes['unsaved_contact'] ?? true);
        }

        $metadata = $contact->metadata ?? [];
        if (!is_array($metadata)) {
            $metadata = [];
        }

        $newMetadata = $contactAttributes['metadata'] ?? [];
        if (!is_array($newMetadata)) {
            $newMetadata = [];
        }

        $sanitizedWaId = WhatsAppConversation::sanitizeWaId($waId);
        if ($sanitizedWaId) {
            $newMetadata['wa_id'] = $sanitizedWaId;
        }

        if ($name && !$contact->name) {
            $contact->name = $name;
        }

        if (!$contact->phone && $phone) {
            $contact->phone = WhatsAppConversation::normalizePhone($phone) ?: trim((string) $phone);
        }

        if (array_key_exists('unsaved_contact', $contactAttributes) && $contact->exists === false) {
            $contact->unsaved_contact = (bool) $contactAttributes['unsaved_contact'];
        }

        $contact->metadata = array_replace_recursive($metadata, $newMetadata);
        $contact->save();

        return $contact;
    }

    protected function syncConversationIdentity(
        WhatsAppConversation $conversation,
        WhatsAppContact $contact,
        ?string $phone,
        ?string $waId,
        array $conversationAttributes = [],
        ?int $accountId = null,
    ): WhatsAppConversation {
        $normalizedPhone = WhatsAppConversation::normalizePhone($phone ?: $contact->phone);
        $sanitizedWaId = WhatsAppConversation::sanitizeWaId($waId ?: data_get($contact->metadata, 'wa_id'));
        $effectiveAccountId = $accountId ?? ($conversation->account_id ? (int) $conversation->account_id : null);
        $conversationKey = WhatsAppConversation::buildConversationKey($sanitizedWaId, $normalizedPhone, $contact->id, $effectiveAccountId);

        $updates = [];

        if ($accountId !== null && (int) $conversation->account_id !== (int) $accountId) {
            $updates['account_id'] = $accountId;
        }

        if ((int) $conversation->contact_id !== (int) $contact->id) {
            $updates['contact_id'] = $contact->id;
        }

        if ($normalizedPhone && $conversation->normalized_phone !== $normalizedPhone) {
            $updates['normalized_phone'] = $normalizedPhone;
        }

        if ($sanitizedWaId && $conversation->wa_id !== $sanitizedWaId) {
            $updates['wa_id'] = $sanitizedWaId;
        }

        if ($conversationKey && $conversation->conversation_key !== $conversationKey) {
            $conflictExists = WhatsAppConversation::query()
                ->where('conversation_key', $conversationKey)
                ->whereKeyNot($conversation->id)
                ->exists();

            if (!$conflictExists) {
                $updates['conversation_key'] = $conversationKey;
            }
        }

        if ($conversation->status !== WhatsAppConversation::STATUS_OPEN) {
            $updates['status'] = WhatsAppConversation::STATUS_OPEN;
        }

        foreach (['assigned_to'] as $attribute) {
            if (array_key_exists($attribute, $conversationAttributes) && $conversation->{$attribute} !== $conversationAttributes[$attribute]) {
                $updates[$attribute] = $conversationAttributes[$attribute];
            }
        }

        if ($updates !== []) {
            $conversation->update($updates);
        }

        return $conversation->fresh(['contact', 'assignedUser']);
    }
}

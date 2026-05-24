<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppContact;
use App\Models\WhatsAppConversation;
use Illuminate\Support\Facades\DB;

class ConversationResolverService
{
    public function findExistingConversation(?string $phone = null, ?string $waId = null, ?int $contactId = null): ?WhatsAppConversation
    {
        if ($waId && ($conversation = $this->matchByWaId($waId))) {
            return $conversation;
        }

        if ($phone && ($conversation = $this->matchByPhone($phone))) {
            return $conversation;
        }

        if ($contactId && ($conversation = $this->matchByContact($contactId))) {
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
    ): WhatsAppConversation {
        $contact = $this->resolveContact($phone, $waId, $contactId, $name, $contactAttributes);

        $conversation = $this->findExistingConversation(
            $phone,
            $waId,
            $contact?->id,
        );

        if ($conversation) {
            return $this->syncConversationIdentity($conversation, $contact, $phone, $waId, $conversationAttributes);
        }

        return $this->createIfMissing($phone, $waId, $contact?->id, $name, $contactAttributes, $conversationAttributes);
    }

    public function matchByWaId(string $waId): ?WhatsAppConversation
    {
        $sanitizedWaId = WhatsAppConversation::sanitizeWaId($waId);
        if (!$sanitizedWaId) {
            return null;
        }

        return WhatsAppConversation::query()
            ->with(['contact', 'assignedUser'])
            ->where('wa_id', $sanitizedWaId)
            ->orderByDesc('updated_at')
            ->first();
    }

    public function matchByPhone(string $phone): ?WhatsAppConversation
    {
        $candidates = WhatsAppConversation::phoneCandidates($phone);
        if ($candidates === []) {
            return null;
        }

        return WhatsAppConversation::query()
            ->with(['contact', 'assignedUser'])
            ->where(function ($query) use ($candidates) {
                $query->whereIn('normalized_phone', $candidates)
                    ->orWhereHas('contact', function ($contactQuery) use ($candidates) {
                        $contactQuery->whereIn('phone', $candidates);
                    });
            })
            ->orderByDesc('updated_at')
            ->first();
    }

    public function matchByContact(int $contactId): ?WhatsAppConversation
    {
        return WhatsAppConversation::query()
            ->with(['contact', 'assignedUser'])
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
    ): WhatsAppConversation {
        $contact = $this->resolveContact($phone, $waId, $contactId, $name, $contactAttributes);
        $normalizedPhone = WhatsAppConversation::normalizePhone($phone ?: $contact?->phone);
        $sanitizedWaId = WhatsAppConversation::sanitizeWaId($waId);
        $conversationKey = WhatsAppConversation::buildConversationKey($sanitizedWaId, $normalizedPhone, $contact?->id);

        return DB::transaction(function () use ($contact, $sanitizedWaId, $normalizedPhone, $conversationKey, $conversationAttributes) {
            $conversation = WhatsAppConversation::query()->firstOrCreate(
                ['conversation_key' => $conversationKey],
                array_merge([
                    'contact_id' => $contact->id,
                    'wa_id' => $sanitizedWaId,
                    'normalized_phone' => $normalizedPhone,
                    'status' => WhatsAppConversation::STATUS_OPEN,
                    'unread_count' => 0,
                ], $conversationAttributes)
            );

            return $this->syncConversationIdentity($conversation, $contact, $normalizedPhone, $sanitizedWaId, $conversationAttributes);
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
    ): WhatsAppConversation {
        $normalizedPhone = WhatsAppConversation::normalizePhone($phone ?: $contact->phone);
        $sanitizedWaId = WhatsAppConversation::sanitizeWaId($waId ?: data_get($contact->metadata, 'wa_id'));
        $conversationKey = WhatsAppConversation::buildConversationKey($sanitizedWaId, $normalizedPhone, $contact->id);

        $updates = [];

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

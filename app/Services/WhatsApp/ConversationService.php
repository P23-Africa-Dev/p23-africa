<?php

namespace App\Services\WhatsApp;

use App\Events\WhatsAppConversationAssigned;
use App\Models\User;
use App\Models\WhatsAppContact;
use App\Models\WhatsAppConversation;
use App\Models\WhatsAppNote;
use App\Services\WhatsApp\Hooks\FutureIntegrationBridge;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class ConversationService
{
    public function __construct(
        protected FutureIntegrationBridge $futureHooks,
    ) {}

    public function listConversations(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        return WhatsAppConversation::query()
            ->with(['contact', 'assignedUser'])
            ->search($filters['search'] ?? null)
            ->status($filters['status'] ?? null)
            ->when(isset($filters['assigned_to']) && $filters['assigned_to'] !== '', function ($query) use ($filters) {
                if ($filters['assigned_to'] === 'unassigned') {
                    return $query->whereNull('assigned_to');
                }

                return $query->where('assigned_to', (int) $filters['assigned_to']);
            })
            ->orderByDesc('updated_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getConversationThread(int $conversationId, int $messageLimit = 200): WhatsAppConversation
    {
        $conversation = WhatsAppConversation::query()
            ->with([
                'contact',
                'assignedUser',
                'messages' => fn($query) => $query->orderByDesc('created_at')->limit($messageLimit),
                'notes.admin',
            ])
            ->findOrFail($conversationId);

        $conversation->setRelation(
            'messages',
            $conversation->messages->sortBy('created_at')->values()
        );

        return $conversation;
    }

    public function findOrCreateConversationForPhone(string $phone, ?string $name = null, array $metadata = []): WhatsAppConversation
    {
        $normalizedPhone = $this->normalizePhone($phone);

        $contact = WhatsAppContact::query()->where('phone', $normalizedPhone)->first();

        if (!$contact) {
            $contact = WhatsAppContact::create([
                'name' => $name,
                'phone' => $normalizedPhone,
                'metadata' => $metadata ?: null,
            ]);
        } else {
            $updates = [];

            if ($name && !$contact->name) {
                $updates['name'] = $name;
            }

            if (!empty($metadata)) {
                $updates['metadata'] = array_merge($contact->metadata ?? [], $metadata);
            }

            if (!empty($updates)) {
                $contact->update($updates);
            }
        }

        $conversation = WhatsAppConversation::firstOrCreate(
            ['contact_id' => $contact->id],
            [
                'status' => WhatsAppConversation::STATUS_OPEN,
                'unread_count' => 0,
            ]
        );

        return $conversation->load('contact', 'assignedUser');
    }

    public function markConversationStatus(WhatsAppConversation $conversation, string $status): WhatsAppConversation
    {
        if (!in_array($status, WhatsAppConversation::statuses(), true)) {
            throw new InvalidArgumentException('Invalid conversation status.');
        }

        $previousStatus = $conversation->status;

        if ($previousStatus === $status) {
            return $conversation;
        }

        $conversation->update(['status' => $status]);
        $conversation->refresh();

        $this->futureHooks->onConversationStatusChanged($conversation, $previousStatus, $status);

        return $conversation;
    }

    public function assignConversation(WhatsAppConversation $conversation, ?int $assigneeId): WhatsAppConversation
    {
        $assignee = null;

        if ($assigneeId) {
            $assignee = User::query()
                ->whereKey($assigneeId)
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })
                ->first();

            if (!$assignee) {
                throw new ModelNotFoundException('Selected assignee is not an admin user.');
            }
        }

        $conversation->update([
            'assigned_to' => $assignee?->id,
        ]);

        $conversation->refresh()->load('contact', 'assignedUser');

        try {
            event(new WhatsAppConversationAssigned($conversation, $assignee));
        } catch (\Throwable $exception) {
            Log::warning('WhatsApp assignment realtime event dispatch skipped', [
                'conversation_id' => $conversation->id,
                'assignee_id' => $assignee?->id,
                'error' => $exception->getMessage(),
            ]);
        }

        return $conversation;
    }

    public function addInternalNote(WhatsAppConversation $conversation, int $adminId, string $note): WhatsAppNote
    {
        return WhatsAppNote::create([
            'conversation_id' => $conversation->id,
            'admin_id' => $adminId,
            'note' => $note,
        ]);
    }

    public function markConversationRead(WhatsAppConversation $conversation): void
    {
        $conversation->update(['unread_count' => 0]);
    }

    public function availableAssignees(): Collection
    {
        return User::query()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }

    protected function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?? $phone;
    }
}

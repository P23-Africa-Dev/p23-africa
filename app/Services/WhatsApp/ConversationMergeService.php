<?php

namespace App\Services\WhatsApp;

use App\Models\WhatsAppConversation;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppNote;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ConversationMergeService
{
    public function findDuplicates(): Collection
    {
        return WhatsAppConversation::query()
            ->with(['contact'])
            ->get()
            ->groupBy(function (WhatsAppConversation $conversation) {
                return $this->identityKey($conversation);
            })
            ->filter(function (Collection $group, $key) {
                return $key !== null && $group->count() > 1;
            })
            ->values();
    }

    public function mergeDuplicates(): int
    {
        $mergedCount = 0;

        foreach ($this->findDuplicates() as $group) {
            $mergedCount += $this->mergeConversationGroup($group) ? 1 : 0;
        }

        return $mergedCount;
    }

    public function mergeConversationGroup(Collection $conversations): ?WhatsAppConversation
    {
        if ($conversations->count() < 2) {
            return $conversations->first();
        }

        return DB::transaction(function () use ($conversations) {
            /** @var WhatsAppConversation $canonical */
            $canonical = $conversations
                ->sortByDesc(fn(WhatsAppConversation $conversation) => $conversation->updated_at?->getTimestamp() ?? 0)
                ->first();

            $duplicates = $conversations->filter(fn(WhatsAppConversation $conversation) => $conversation->id !== $canonical->id);

            $latestConversation = $conversations
                ->sortByDesc(fn(WhatsAppConversation $conversation) => $conversation->updated_at?->getTimestamp() ?? 0)
                ->first();

            $canonical->update([
                'contact_id' => $canonical->contact_id ?: $conversations->pluck('contact_id')->filter()->first(),
                'wa_id' => $canonical->wa_id ?: $conversations->pluck('wa_id')->filter()->first(),
                'normalized_phone' => $canonical->normalized_phone ?: $conversations->pluck('normalized_phone')->filter()->first(),
                'last_message' => $latestConversation?->last_message,
                'unread_count' => (int) $conversations->sum('unread_count'),
                'status' => WhatsAppConversation::STATUS_OPEN,
            ]);

            foreach ($duplicates as $duplicate) {
                WhatsAppMessage::query()
                    ->where('conversation_id', $duplicate->id)
                    ->update(['conversation_id' => $canonical->id]);

                WhatsAppNote::query()
                    ->where('conversation_id', $duplicate->id)
                    ->update(['conversation_id' => $canonical->id]);

                $duplicate->delete();
            }

            return $canonical->fresh(['contact', 'assignedUser']);
        });
    }

    protected function identityKey(WhatsAppConversation $conversation): ?string
    {
        if ($conversation->wa_id) {
            return 'wa:' . $conversation->wa_id;
        }

        if ($conversation->normalized_phone) {
            return 'phone:' . $conversation->normalized_phone;
        }

        if ($conversation->contact_id) {
            return 'contact:' . $conversation->contact_id;
        }

        return null;
    }
}

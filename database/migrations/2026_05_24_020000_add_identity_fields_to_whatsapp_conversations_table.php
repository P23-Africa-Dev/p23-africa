<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->string('wa_id')->nullable()->after('contact_id');
            $table->string('normalized_phone')->nullable()->after('wa_id');
            $table->string('conversation_key')->nullable()->after('normalized_phone');

            $table->index('wa_id');
            $table->index('normalized_phone');
        });

        $conversations = DB::table('whatsapp_conversations as conversations')
            ->leftJoin('whatsapp_contacts as contacts', 'contacts.id', '=', 'conversations.contact_id')
            ->orderBy('conversations.updated_at')
            ->orderBy('conversations.id')
            ->get([
                'conversations.id',
                'conversations.contact_id',
                'contacts.phone as contact_phone',
            ]);

        $seenKeys = [];

        foreach ($conversations as $conversation) {
            $normalizedPhone = $this->normalizePhone($conversation->contact_phone);
            $baseKey = $this->buildConversationKey(null, $normalizedPhone, $conversation->contact_id ? (int) $conversation->contact_id : null);
            $storedKey = $baseKey;

            if ($storedKey === null) {
                $storedKey = 'row_' . sha1('conversation:' . $conversation->id);
            } elseif (isset($seenKeys[$storedKey])) {
                $storedKey = 'dup_' . sha1($storedKey . ':' . $conversation->id);
            }

            $seenKeys[$storedKey] = true;

            DB::table('whatsapp_conversations')
                ->where('id', $conversation->id)
                ->update([
                    'normalized_phone' => $normalizedPhone,
                    'conversation_key' => $storedKey,
                ]);
        }

        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->unique('conversation_key');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->dropUnique(['conversation_key']);
            $table->dropIndex(['wa_id']);
            $table->dropIndex(['normalized_phone']);
            $table->dropColumn(['wa_id', 'normalized_phone', 'conversation_key']);
        });
    }

    private function normalizePhone(?string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone) ?? '';

        return $digits !== '' ? '+' . $digits : null;
    }

    private function buildConversationKey(?string $waId, ?string $normalizedPhone, ?int $contactId): ?string
    {
        $waDigits = preg_replace('/\D+/', '', (string) $waId) ?? '';
        if ($waDigits !== '') {
            return 'wa_' . sha1($waDigits);
        }

        if ($normalizedPhone) {
            return 'ph_' . sha1($normalizedPhone);
        }

        if ($contactId) {
            return 'ct_' . $contactId;
        }

        return null;
    }
};

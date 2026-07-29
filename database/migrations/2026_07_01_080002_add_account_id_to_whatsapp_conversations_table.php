<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->nullable()->after('id');
            $table->foreign('account_id')
                ->references('id')
                ->on('whatsapp_accounts')
                ->nullOnDelete();
            $table->index('account_id');
        });

        // Backfill: assign every existing conversation to the oldest account (if any).
        $defaultAccountId = DB::table('whatsapp_accounts')->orderBy('id')->value('id');

        if ($defaultAccountId) {
            DB::table('whatsapp_conversations')
                ->whereNull('account_id')
                ->update(['account_id' => $defaultAccountId]);
        }

        // Drop existing unique on conversation_key so we can rebuild keys with account prefix.
        try {
            Schema::table('whatsapp_conversations', function (Blueprint $table) {
                $table->dropUnique(['conversation_key']);
            });
        } catch (\Throwable $exception) {
            // Unique may already be missing on some environments; ignore.
        }

        // Rebuild conversation_key values to include the account prefix so multiple accounts
        // can safely host the same customer without colliding on the shared unique index.
        $rows = DB::table('whatsapp_conversations')
            ->select(['id', 'account_id', 'conversation_key'])
            ->get();

        foreach ($rows as $row) {
            $accountId = $row->account_id ? (int) $row->account_id : 0;
            $existingKey = (string) ($row->conversation_key ?? '');
            $prefix = 'acc' . $accountId . '_';

            if ($existingKey === '') {
                $newKey = $prefix . 'row_' . sha1('conversation:' . $row->id);
            } elseif (str_starts_with($existingKey, $prefix)) {
                $newKey = $existingKey;
            } else {
                $newKey = $prefix . $existingKey;
            }

            DB::table('whatsapp_conversations')
                ->where('id', $row->id)
                ->update(['conversation_key' => $newKey]);
        }

        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->unique('conversation_key');
        });
    }

    public function down(): void
    {
        try {
            Schema::table('whatsapp_conversations', function (Blueprint $table) {
                $table->dropUnique(['conversation_key']);
            });
        } catch (\Throwable) {
            // ignore
        }

        // Strip account prefix from conversation keys so previous unique constraint remains valid.
        $rows = DB::table('whatsapp_conversations')
            ->select(['id', 'conversation_key'])
            ->get();

        foreach ($rows as $row) {
            $key = (string) ($row->conversation_key ?? '');
            if (preg_match('/^acc\d+_(.+)$/', $key, $matches)) {
                DB::table('whatsapp_conversations')
                    ->where('id', $row->id)
                    ->update(['conversation_key' => $matches[1]]);
            }
        }

        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->unique('conversation_key');
        });

        Schema::table('whatsapp_conversations', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropIndex(['account_id']);
            $table->dropColumn('account_id');
        });
    }
};

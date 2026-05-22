<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('whatsapp_messages', 'status_dirty')) {
                $table->boolean('status_dirty')->default(false)->after('status');
            }

            if (!Schema::hasColumn('whatsapp_messages', 'status_updated_at')) {
                $table->timestamp('status_updated_at')->nullable()->after('status_dirty');
            }
        });

        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->index(['conversation_id', 'status_dirty'], 'wa_messages_conversation_status_dirty_idx');
            $table->index(['conversation_id', 'status_updated_at'], 'wa_messages_conversation_status_updated_idx');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->dropIndex('wa_messages_conversation_status_dirty_idx');
            $table->dropIndex('wa_messages_conversation_status_updated_idx');

            if (Schema::hasColumn('whatsapp_messages', 'status_updated_at')) {
                $table->dropColumn('status_updated_at');
            }

            if (Schema::hasColumn('whatsapp_messages', 'status_dirty')) {
                $table->dropColumn('status_dirty');
            }
        });
    }
};

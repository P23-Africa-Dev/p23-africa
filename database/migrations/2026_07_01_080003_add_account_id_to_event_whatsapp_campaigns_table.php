<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_whatsapp_campaigns', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->nullable()->after('template_id');
            $table->foreign('account_id')
                ->references('id')
                ->on('whatsapp_accounts')
                ->nullOnDelete();
            $table->index('account_id');
        });
    }

    public function down(): void
    {
        Schema::table('event_whatsapp_campaigns', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropIndex(['account_id']);
            $table->dropColumn('account_id');
        });
    }
};

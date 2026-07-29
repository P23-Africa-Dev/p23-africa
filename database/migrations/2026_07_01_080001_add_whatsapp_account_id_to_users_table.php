<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('whatsapp_account_id')->nullable()->after('id');
            $table->foreign('whatsapp_account_id')
                ->references('id')
                ->on('whatsapp_accounts')
                ->nullOnDelete();
            $table->index('whatsapp_account_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['whatsapp_account_id']);
            $table->dropIndex(['whatsapp_account_id']);
            $table->dropColumn('whatsapp_account_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('whatsapp_contacts', function (Blueprint $table) {
            $table->string('country_code', 8)->nullable()->after('phone');
            $table->string('email')->nullable()->after('country_code');
            $table->string('company')->nullable()->after('email');
            $table->text('notes')->nullable()->after('company');
            $table->string('avatar', 2048)->nullable()->after('notes');
            $table->boolean('is_whatsapp')->nullable()->after('avatar');
            $table->string('validation_status', 50)->nullable()->after('is_whatsapp');
            $table->timestamp('validated_at')->nullable()->after('validation_status');
            $table->timestamp('validation_cache_until')->nullable()->after('validated_at');
            $table->timestamp('last_chat_at')->nullable()->after('validation_cache_until');
            $table->boolean('unsaved_contact')->default(false)->after('last_chat_at');

            $table->index('company');
            $table->index('unsaved_contact');
            $table->index('validation_status');
            $table->index('last_chat_at');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_contacts', function (Blueprint $table) {
            $table->dropIndex(['company']);
            $table->dropIndex(['unsaved_contact']);
            $table->dropIndex(['validation_status']);
            $table->dropIndex(['last_chat_at']);
            $table->dropColumn([
                'country_code',
                'email',
                'company',
                'notes',
                'avatar',
                'is_whatsapp',
                'validation_status',
                'validated_at',
                'validation_cache_until',
                'last_chat_at',
                'unsaved_contact',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('whatsapp_accounts', function (Blueprint $table) {
            $table->string('display_name_status')->default('pending_approval')->after('status');
            $table->string('profile_sync_status')->default('pending_approval')->after('display_name_status');
            $table->timestamp('last_profile_sync')->nullable()->after('profile_sync_status');
            $table->string('meta_profile_id')->nullable()->after('last_profile_sync');
            $table->string('profile_image_url', 2048)->nullable()->after('meta_profile_id');

            $table->index('display_name_status');
            $table->index('profile_sync_status');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_accounts', function (Blueprint $table) {
            $table->dropIndex(['display_name_status']);
            $table->dropIndex(['profile_sync_status']);
            $table->dropColumn([
                'display_name_status',
                'profile_sync_status',
                'last_profile_sync',
                'meta_profile_id',
                'profile_image_url',
            ]);
        });
    }
};

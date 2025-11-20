<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('email_sent_at');
            $table->string('session_id')->nullable()->after('status');
            $table->integer('keystroke_count')->default(0)->after('session_id');
            $table->timestamp('last_activity_at')->nullable()->after('keystroke_count');
            $table->json('field_interactions')->nullable()->after('last_activity_at');
            $table->timestamp('draft_created_at')->nullable()->after('field_interactions');
            $table->timestamp('submitted_at')->nullable()->after('draft_created_at');

            $table->index(['status']);
            $table->index(['session_id']);
            $table->index(['last_activity_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['session_id']);
            $table->dropIndex(['last_activity_at']);

            $table->dropColumn([
                'status',
                'session_id',
                'keystroke_count',
                'last_activity_at',
                'field_interactions',
                'draft_created_at',
                'submitted_at'
            ]);
        });
    }
};

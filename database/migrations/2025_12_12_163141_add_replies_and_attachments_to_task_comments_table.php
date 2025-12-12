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
        Schema::table('task_comments', function (Blueprint $table) {
            // Parent comment ID for replies
            $table->foreignId('parent_id')->nullable()->after('user_id')
                ->constrained('task_comments')->onDelete('cascade');

            // File attachment fields
            $table->string('attachment_path')->nullable()->after('comment');
            $table->string('attachment_name')->nullable()->after('attachment_path');
            $table->string('attachment_type')->nullable()->after('attachment_name');

            // Mentioned users (stored as JSON array of user IDs)
            $table->json('mentioned_users')->nullable()->after('attachment_type');

            // Index for faster reply lookups
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_comments', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['parent_id']);
            $table->dropColumn([
                'parent_id',
                'attachment_path',
                'attachment_name',
                'attachment_type',
                'mentioned_users'
            ]);
        });
    }
};

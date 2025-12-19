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
        Schema::create('lead_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            // Activity Type
            $table->enum('type', [
                'note',
                'email',
                'call',
                'meeting',
                'task',
                'status_change',
                'follow_up',
                'proposal_sent',
                'document',
                'other'
            ])->default('note');

            // Activity Details
            $table->string('title');
            $table->text('description')->nullable();

            // For status changes
            $table->string('old_value')->nullable();
            $table->string('new_value')->nullable();

            // For scheduled activities
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->boolean('is_completed')->default(false);

            // Metadata
            $table->json('metadata')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('type');
            $table->index('scheduled_at');
            $table->index('is_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_activities');
    }
};

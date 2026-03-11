<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('supervisor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('cascade');

            $table->string('type'); // annual, sick, personal, maternity, paternity, bereavement, other
            $table->text('reason');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration_days');

            // Status flow: pending -> approved/rejected/modified
            // If modified: sender can respond with okay/not_okay
            // Then supervisor makes final decision: approved/rejected
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'modified',
                'sender_okay',
                'sender_not_okay',
            ])->default('pending');

            // Supervisor can modify the request with a note and new dates
            $table->text('supervisor_note')->nullable();
            $table->date('modified_start_date')->nullable();
            $table->date('modified_end_date')->nullable();
            $table->integer('modified_duration_days')->nullable();

            // Sender response to modifications
            $table->text('sender_response_note')->nullable();
            $table->timestamp('sender_responded_at')->nullable();

            // Final decision tracking
            $table->timestamp('decided_at')->nullable();
            $table->text('decision_note')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['supervisor_id', 'status']);
            $table->index(['organization_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};

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
        Schema::create('scheduled_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_application_id')->constrained()->onDelete('cascade');
            $table->date('session_date');
            $table->time('session_time');
            $table->string('timezone', 10);
            $table->string('meet_link')->nullable(); // Google Meet link
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'rescheduled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_sessions');
    }
};

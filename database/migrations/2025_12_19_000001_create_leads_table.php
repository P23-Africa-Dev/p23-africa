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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('job_title')->nullable();
            $table->string('website')->nullable();

            // Location
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();

            // Lead Details
            $table->enum('status', ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost'])->default('new');
            $table->enum('source', ['website', 'referral', 'social_media', 'email_campaign', 'cold_call', 'event', 'advertisement', 'other'])->default('website');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->decimal('estimated_value', 12, 2)->nullable();
            $table->string('currency', 3)->default('USD');

            // Interest & Requirements
            $table->json('interested_services')->nullable();
            $table->text('requirements')->nullable();
            $table->text('notes')->nullable();

            // Assignment
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');

            // Tracking
            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamp('next_follow_up')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->string('lost_reason')->nullable();

            // Reference to source record
            $table->string('source_type')->nullable(); // e.g., 'contact_submission', 'consultation_application'
            $table->unsignedBigInteger('source_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('source');
            $table->index('priority');
            $table->index('assigned_to');
            $table->index('next_follow_up');
            $table->index(['source_type', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};

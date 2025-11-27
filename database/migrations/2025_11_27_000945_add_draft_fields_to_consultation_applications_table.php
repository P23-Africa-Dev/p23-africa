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
        Schema::table('consultation_applications', function (Blueprint $table) {
            // Add draft tracking fields
            $table->string('session_id')->nullable()->after('id');
            $table->enum('status', ['draft', 'pending', 'payment_pending', 'paid', 'scheduled', 'completed', 'cancelled'])->default('pending')->change();

            // Draft-specific fields
            $table->json('draft_data')->nullable()->after('status');
            $table->timestamp('draft_created_at')->nullable()->after('draft_data');
            $table->timestamp('submitted_at')->nullable()->after('draft_created_at');

            // Form interaction tracking
            $table->integer('keystroke_count')->default(0)->after('submitted_at');
            $table->json('field_interactions')->nullable()->after('keystroke_count');

            // User tracking
            $table->string('ip_address')->nullable()->after('field_interactions');
            $table->text('user_agent')->nullable()->after('ip_address');
            $table->timestamp('last_activity_at')->nullable()->after('user_agent');

            // Optional fields from the form
            $table->string('website_or_handle')->nullable()->after('company');

            // Make some fields nullable for drafts
            $table->string('name')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('company')->nullable()->change();
            $table->string('industry')->nullable()->change();
            $table->text('business_description')->nullable()->change();
            $table->json('selected_goals')->nullable()->change();
            $table->json('selected_challenges')->nullable()->change();
            $table->text('desired_outcomes')->nullable()->change();

            // Index for performance
            $table->index('session_id');
            $table->index(['status', 'created_at']);
            $table->index('last_activity_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultation_applications', function (Blueprint $table) {
            // Remove draft tracking fields
            $table->dropColumn([
                'session_id',
                'draft_data',
                'draft_created_at',
                'submitted_at',
                'keystroke_count',
                'field_interactions',
                'ip_address',
                'user_agent',
                'last_activity_at',
                'website_or_handle'
            ]);

            // Revert status enum
            $table->enum('status', ['pending', 'payment_pending', 'paid', 'scheduled', 'completed', 'cancelled'])->default('pending')->change();

            // Revert nullable fields back to required
            $table->string('name')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->string('company')->nullable(false)->change();
            $table->string('industry')->nullable(false)->change();
            $table->text('business_description')->nullable(false)->change();
            $table->json('selected_goals')->nullable(false)->change();
            $table->json('selected_challenges')->nullable(false)->change();
            $table->text('desired_outcomes')->nullable(false)->change();
        });
    }
};

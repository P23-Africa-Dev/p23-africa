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
        // Add booking_fields column to events table
        Schema::table('events', function (Blueprint $table) {
            $table->json('booking_fields')->nullable();
        });

        // Add additional fields to seats table
        Schema::table('seats', function (Blueprint $table) {
            $table->string('job_title')->nullable();
            $table->string('organization')->nullable();
            $table->string('location')->nullable();
            $table->string('industry')->nullable();
            $table->text('expectations')->nullable();
            $table->string('referral_source')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove booking_fields column from events table
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('booking_fields');
        });

        // Remove additional fields from seats table
        Schema::table('seats', function (Blueprint $table) {
            $table->dropColumn([
                'job_title',
                'organization',
                'location',
                'industry',
                'expectations',
                'referral_source'
            ]);
        });
    }
};

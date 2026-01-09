<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the source enum to include noel_waitlist
        DB::statement("ALTER TABLE leads MODIFY COLUMN source ENUM('website', 'referral', 'social_media', 'email_campaign', 'cold_call', 'event', 'advertisement', 'noel_waitlist', 'other') DEFAULT 'website'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE leads MODIFY COLUMN source ENUM('website', 'referral', 'social_media', 'email_campaign', 'cold_call', 'event', 'advertisement', 'other') DEFAULT 'website'");
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Fixes:
     * 1. Change `status` from ENUM to VARCHAR(50) so dynamically-created
     *    statuses (e.g. "onboarded") are not truncated by the DB.
     * 2. Widen `estimated_value` from DECIMAL(12,2) to DECIMAL(15,2) so
     *    values up to 9,999,999,999,999.99 are supported (was failing at
     *    10,000,000,000 which exceeds DECIMAL(12,2) max of 9,999,999,999.99).
     */
    public function up(): void
    {
        // Change status from ENUM to VARCHAR(50) — keeps existing data intact
        DB::statement("ALTER TABLE leads MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'new'");

        // Widen estimated_value from DECIMAL(12,2) to DECIMAL(15,2)
        DB::statement("ALTER TABLE leads MODIFY COLUMN estimated_value DECIMAL(15,2) NULL DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore status to the last known ENUM definition
        DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('new','contacted','qualified','proposal','negotiation','won','lost','unqualified') DEFAULT 'new'");

        // Restore estimated_value to DECIMAL(12,2)
        DB::statement("ALTER TABLE leads MODIFY COLUMN estimated_value DECIMAL(12,2) NULL DEFAULT NULL");
    }
};

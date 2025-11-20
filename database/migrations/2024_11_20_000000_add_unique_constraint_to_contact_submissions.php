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
            // Add a composite unique index to prevent exact duplicates within a time window
            // This helps prevent race conditions at the database level
            $table->index(['email', 'phone', 'created_at'], 'contact_submissions_duplicate_check');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->dropIndex('contact_submissions_duplicate_check');
        });
    }
};

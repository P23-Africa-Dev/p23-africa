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
            // Make email field nullable to support draft saves
            $table->string('email')->nullable()->change();
            // Also make name and message nullable for partial draft saves
            $table->string('name')->nullable()->change();
            $table->text('message')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table) {
            // Revert back to NOT NULL (only if there are no null values)
            $table->string('email')->nullable(false)->change();
            $table->string('name')->nullable(false)->change();
            $table->text('message')->nullable(false)->change();
        });
    }
};

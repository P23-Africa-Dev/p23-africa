<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Scope users email uniqueness to organization boundary.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop legacy global email uniqueness.
            $table->dropUnique('users_email_unique');

            // Enforce per-organization uniqueness.
            $table->unique(['organization_id', 'email'], 'users_org_email_unique');
        });
    }

    /**
     * Restore legacy global email uniqueness.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_org_email_unique');
            $table->unique('email', 'users_email_unique');
        });
    }
};

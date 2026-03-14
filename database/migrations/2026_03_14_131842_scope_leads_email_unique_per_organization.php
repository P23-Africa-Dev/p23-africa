<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Replace the global unique index on email with a composite
     * unique index on (organization_id, email) so that different
     * organisations can each have a lead with the same email.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Drop the old global unique constraint
            $table->dropUnique('leads_email_unique');

            // Add composite unique: same email is allowed across different orgs
            // NULL organization_id (super-admin leads) are handled by MySQL's
            // treatment of NULL in unique indexes (each NULL is distinct).
            $table->unique(['organization_id', 'email'], 'leads_org_email_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropUnique('leads_org_email_unique');
            $table->unique('email', 'leads_email_unique');
        });
    }
};

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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('employee_count')->nullable()->after('company');
            $table->string('company_linkedin_profile')->nullable()->after('website');
            $table->string('ceo_linkedin_profile')->nullable()->after('company_linkedin_profile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['employee_count', 'company_linkedin_profile', 'ceo_linkedin_profile']);
        });
    }
};

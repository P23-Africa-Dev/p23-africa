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
        Schema::table('brn_submissions', function (Blueprint $table) {
            $table->string('annual_revenue')->nullable()->after('years_of_operation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brn_submissions', function (Blueprint $table) {
            $table->dropColumn('annual_revenue');
        });
    }
};

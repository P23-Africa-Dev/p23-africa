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
            // Rename full_name to name to match model
            $table->renameColumn('full_name', 'name');

            // Add new fields for enhanced form
            $table->string('reg_number')->nullable()->after('company_name');
            $table->string('website')->nullable()->after('reg_number');
            $table->integer('year_established')->nullable()->after('website');
            $table->string('industry')->nullable()->after('year_established');
            $table->text('categories')->nullable()->after('industry');
            $table->text('offer_value')->nullable()->after('categories');
            $table->string('b2b_interest')->default('yes')->after('offer_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brn_submissions', function (Blueprint $table) {
            // Rename back to full_name
            $table->renameColumn('name', 'full_name');

            // Drop added fields
            $table->dropColumn([
                'reg_number',
                'website',
                'year_established',
                'industry',
                'categories',
                'offer_value',
                'b2b_interest'
            ]);
        });
    }
};

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
            // Add name field only if it doesn't exist
            if (!Schema::hasColumn('brn_submissions', 'name')) {
                $table->string('name')->nullable()->after('id');
            }

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
            // Drop added fields
            $table->dropColumn([
                'name',
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

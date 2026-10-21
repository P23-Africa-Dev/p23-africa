<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Social referrers (Instagram, Facebook, etc.) append long tracking
     * parameters (fbclid, utm_*) that exceed VARCHAR(255).
     */
    public function up(): void
    {
        Schema::table('page_visits', function (Blueprint $table) {
            $table->text('page_url')->change();
        });

        if (Schema::hasColumn('clicks', 'page_url')) {
            Schema::table('clicks', function (Blueprint $table) {
                $table->text('page_url')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_visits', function (Blueprint $table) {
            $table->string('page_url')->change();
        });

        if (Schema::hasColumn('clicks', 'page_url')) {
            Schema::table('clicks', function (Blueprint $table) {
                $table->string('page_url')->nullable()->change();
            });
        }
    }
};

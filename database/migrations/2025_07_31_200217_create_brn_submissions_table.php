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
        Schema::create('brn_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('linkedin')->nullable();
            $table->string('company_name');
            $table->string('country');
            $table->string('position');
            $table->string('years_of_operation'); // "0-5", "6-10", etc
            $table->string('number_of_employees'); // "1-10", "11-50", etc
            $table->string('selected_outcome'); // outcome selected by the user
            $table->text('goals')->nullable();
            $table->string('status')->default('pending'); // approved, declined, pending
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brn_submissions');
    }
};

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
        Schema::create('consultation_applications', function (Blueprint $table) {
            $table->id();
            // Step 1: Basic Information
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('company');
            $table->string('industry');
            $table->text('business_description');

            // Step 2: Goals (stored as JSON array)
            $table->json('selected_goals');

            // Step 3: Challenges (stored as JSON array)
            $table->json('selected_challenges');

            // Step 4: Desired Outcomes
            $table->text('desired_outcomes');

            // Application status
            $table->enum('status', ['pending', 'payment_pending', 'paid', 'scheduled', 'completed', 'cancelled'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_applications');
    }
};

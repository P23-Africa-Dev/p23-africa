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
        Schema::create('ultimate_funding_downloads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('country');
            $table->string('company');
            $table->boolean('payment_completed')->default(false);
            $table->string('payment_reference')->nullable();
            $table->decimal('amount_paid', 10, 2)->default(0.00);
            $table->string('payment_status')->default('pending'); // pending, successful, failed
            $table->timestamp('payment_date')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            $table->json('payment_data')->nullable(); // Store Flutterwave response
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ultimate_funding_downloads');
    }
};

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
        Schema::create('tracked_link_daily_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tracked_link_id')->constrained('tracked_links')->cascadeOnDelete();
            $table->date('date');
            $table->unsignedBigInteger('total_clicks')->default(0);
            $table->unsignedBigInteger('unique_clicks')->default(0);
            $table->unsignedBigInteger('repeated_clicks')->default(0);
            $table->timestamps();

            $table->unique(['tracked_link_id', 'date']);
            $table->index(['date', 'tracked_link_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracked_link_daily_stats');
    }
};

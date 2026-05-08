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
        Schema::create('tracked_link_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tracked_link_id')->constrained('tracked_links')->cascadeOnDelete();
            $table->timestamp('clicked_at')->index();
            $table->string('ip_address', 45)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->string('device_type', 20)->nullable()->index();
            $table->string('browser')->nullable()->index();
            $table->string('browser_version')->nullable();
            $table->string('platform')->nullable()->index();
            $table->string('platform_version')->nullable();
            $table->text('referrer')->nullable();
            $table->string('session_id')->nullable()->index();
            $table->string('visitor_fingerprint', 64)->nullable()->index();
            $table->boolean('is_unique_click')->default(false)->index();
            $table->timestamps();

            $table->index(['tracked_link_id', 'clicked_at'], 'tlc_link_date_idx');
            $table->index(['tracked_link_id', 'visitor_fingerprint'], 'tlc_link_fingerprint_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracked_link_clicks');
    }
};

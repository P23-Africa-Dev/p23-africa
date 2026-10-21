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
        if (Schema::hasTable('page_visits')) {
            return;
        }

        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->string('route_name');
            $table->string('page_title')->nullable();
            $table->text('page_url');
            $table->string('device_type');
            $table->string('ip_address');
            $table->text('user_agent');
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('platform')->nullable();
            $table->string('platform_version')->nullable();
            $table->text('referrer')->nullable();
            $table->string('session_id');
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('timezone')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_unique_visitor')->default(false);
            $table->integer('screen_width')->nullable();
            $table->integer('screen_height')->nullable();
            $table->integer('time_on_page')->nullable(); // in seconds
            $table->timestamps();

            // Indexes for better performance
            $table->index(['route_name', 'created_at']);
            $table->index(['device_type', 'created_at']);
            $table->index(['country', 'created_at']);
            $table->index(['session_id', 'created_at']);
            $table->index(['ip_address', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_visits');
    }
};

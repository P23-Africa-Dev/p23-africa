<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tracked_links', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tracking_code')->unique();
            $table->text('destination_url');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('total_clicks')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'tracking_code']);
        });

        DB::table('tracked_links')->insert([
            'name' => 'Default Redirect Link',
            'tracking_code' => 'default-link',
            'destination_url' => 'https://mydomain.com',
            'description' => 'Default placeholder tracked redirect link.',
            'is_active' => true,
            'total_clicks' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracked_links');
    }
};

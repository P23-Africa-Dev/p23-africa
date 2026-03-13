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
        Schema::create('lead_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');           // e.g. "New", "Contacted"
            $table->string('slug');           // e.g. "new", "contacted"
            $table->string('color', 20)->default('secondary'); // Bootstrap color class
            $table->unsignedInteger('position')->default(0);   // Sort order in pipeline
            $table->boolean('is_default')->default(false);     // Default status for new leads
            $table->boolean('is_won')->default(false);         // Marks a "won" terminal state
            $table->boolean('is_lost')->default(false);        // Marks a "lost" terminal state
            $table->timestamps();

            $table->unique(['organization_id', 'slug']);
            $table->index(['organization_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_statuses');
    }
};

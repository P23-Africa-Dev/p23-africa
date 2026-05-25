<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_whatsapp_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('whatsapp_templates')->nullOnDelete();
            $table->unsignedInteger('total_contacts')->default(0);
            $table->unsignedInteger('valid_contacts')->default(0);
            $table->unsignedInteger('sent')->default(0);
            $table->unsignedInteger('failed')->default(0);
            $table->string('status', 40)->default('draft');
            $table->string('source_type', 40)->default('event_contacts');
            $table->json('audience_filters')->nullable();
            $table->timestamp('queued_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('source_type');
            $table->index('queued_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_whatsapp_campaigns');
    }
};

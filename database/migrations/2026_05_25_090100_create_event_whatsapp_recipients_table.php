<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_whatsapp_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('event_whatsapp_campaigns')->cascadeOnDelete();
            $table->foreignId('event_registration_id')->nullable()->constrained('seats')->nullOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('whatsapp_contacts')->nullOnDelete();
            $table->string('phone', 40);
            $table->string('status', 40)->default('queued');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->unsignedBigInteger('message_id')->nullable();
            $table->json('variables')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('phone');
            $table->index(['campaign_id', 'status']);
            $table->index('event_registration_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_whatsapp_recipients');
    }
};

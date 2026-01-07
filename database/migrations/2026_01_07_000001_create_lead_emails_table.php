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
        Schema::create('lead_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Admin who sent/received
            $table->string('from_email');
            $table->string('from_name')->nullable();
            $table->string('to_email');
            $table->string('to_name')->nullable();
            $table->string('subject');
            $table->longText('body');
            $table->longText('body_html')->nullable();
            $table->enum('direction', ['outgoing', 'incoming'])->default('outgoing');
            $table->string('message_id')->nullable()->unique(); // For tracking replies
            $table->string('in_reply_to')->nullable(); // References the message_id being replied to
            $table->string('thread_id')->nullable(); // Group related emails together
            $table->enum('status', ['draft', 'sent', 'delivered', 'failed', 'received'])->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->json('attachments')->nullable();
            $table->json('metadata')->nullable(); // Store any additional data from webhooks
            $table->timestamps();

            $table->index(['lead_id', 'thread_id']);
            $table->index('message_id');
            $table->index('in_reply_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_emails');
    }
};

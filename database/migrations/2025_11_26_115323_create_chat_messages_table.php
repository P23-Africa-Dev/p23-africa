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
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('consultation_application_id');
            $table->string('sender_name');
            $table->string('sender_email')->nullable();
            $table->text('message');
            $table->enum('sender_type', ['user', 'admin'])->default('user');
            $table->boolean('is_read')->default(false);
            $table->timestamp('sent_at');
            $table->timestamps();

            $table->foreign('consultation_application_id')
                ->references('id')
                ->on('consultation_applications')
                ->onDelete('cascade');

            $table->index(['consultation_application_id', 'sent_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};

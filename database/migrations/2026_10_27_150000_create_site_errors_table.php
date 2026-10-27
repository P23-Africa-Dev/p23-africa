<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_errors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('method', 10);
            $table->text('path');
            $table->text('query')->nullable();
            $table->unsignedSmallInteger('status')->default(500);
            $table->string('exception_class');
            $table->text('message');
            $table->text('file')->nullable();
            $table->unsignedInteger('line')->nullable();
            $table->text('trace')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_errors');
    }
};

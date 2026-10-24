<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_translations', function (Blueprint $table) {
            $table->id();
            $table->string('translatable_type', 100);
            $table->unsignedBigInteger('translatable_id');
            $table->string('locale', 5);
            $table->string('field', 64);
            $table->longText('value')->nullable();
            $table->timestamps();

            $table->index(['translatable_type', 'translatable_id']);
            $table->unique(
                ['translatable_type', 'translatable_id', 'locale', 'field'],
                'content_translations_unique'
            );
        });

        foreach (['contact_submissions', 'brn_submissions', 'consultation_applications'] as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'locale')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->string('locale', 5)->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('content_translations');

        foreach (['contact_submissions', 'brn_submissions', 'consultation_applications'] as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'locale')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('locale');
                });
            }
        }
    }
};

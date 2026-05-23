<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            $table->string('template_format', 20)->default('json')->after('language');
            $table->longText('rendered_content')->nullable()->after('template_json');
            $table->longText('preview_content')->nullable()->after('rendered_content');
            $table->json('editor_metadata')->nullable()->after('preview_content');

            $table->index('template_format');
        });

        DB::table('whatsapp_templates')->whereNull('template_format')->update([
            'template_format' => 'json',
        ]);
    }

    public function down(): void
    {
        Schema::table('whatsapp_templates', function (Blueprint $table) {
            $table->dropIndex(['template_format']);
            $table->dropColumn([
                'template_format',
                'rendered_content',
                'preview_content',
                'editor_metadata',
            ]);
        });
    }
};

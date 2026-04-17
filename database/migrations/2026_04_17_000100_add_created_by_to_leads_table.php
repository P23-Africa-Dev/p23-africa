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
        Schema::table('leads', function (Blueprint $table) {
            $table->foreignId('created_by')
                ->nullable()
                ->after('organization_id')
                ->constrained('users')
                ->nullOnDelete();

            $table->index('created_by', 'leads_created_by_idx');
            $table->index(['organization_id', 'created_by', 'created_at'], 'leads_org_creator_created_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex('leads_org_creator_created_idx');
            $table->dropIndex('leads_created_by_idx');
            $table->dropConstrainedForeignId('created_by');
        });
    }
};

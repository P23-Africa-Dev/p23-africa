<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the status enum to include 'unqualified'
        DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost', 'unqualified') DEFAULT 'new'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First update any 'unqualified' leads to 'new' status
        DB::table('leads')->where('status', 'unqualified')->update(['status' => 'new']);

        // Revert the status enum
        DB::statement("ALTER TABLE leads MODIFY COLUMN status ENUM('new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost') DEFAULT 'new'");
    }
};

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
        // Update the role name from 'staff' to 'manager' in the roles table
        DB::table('roles')->where('name', 'staff')->update(['name' => 'manager']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the role name from 'manager' back to 'staff'
        DB::table('roles')->where('name', 'manager')->update(['name' => 'staff']);
    }
};

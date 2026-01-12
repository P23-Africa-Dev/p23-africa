<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\LeadDrive;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->foreignId('drive_id')->nullable()->after('id')->constrained('lead_drives')->onDelete('set null');
        });

        // Create default drives
        $leadBank = LeadDrive::create([
            'name' => 'Lead Bank',
            'slug' => 'lead-bank',
            'description' => 'General lead repository',
            'color' => '#0d6efd',
            'is_default' => true,
        ]);

        LeadDrive::create([
            'name' => 'NOEL Waitlist',
            'slug' => 'noel-waitlist',
            'description' => 'NOEL program waitlist leads',
            'color' => '#198754',
            'is_default' => false,
        ]);

        // Assign existing leads to Lead Bank
        \App\Models\Lead::whereNull('drive_id')->update(['drive_id' => $leadBank->id]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['drive_id']);
            $table->dropColumn('drive_id');
        });
    }
};

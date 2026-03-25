<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('consultation_applications', function (Blueprint $table) {
            $table->string('booking_token', 64)->nullable()->after('session_id');
            $table->unique('booking_token');
        });

        DB::table('consultation_applications')
            ->whereNull('booking_token')
            ->orderBy('id')
            ->chunkById(200, function ($applications) {
                foreach ($applications as $application) {
                    DB::table('consultation_applications')
                        ->where('id', $application->id)
                        ->update(['booking_token' => Str::uuid()->toString()]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultation_applications', function (Blueprint $table) {
            $table->dropUnique('consultation_applications_booking_token_unique');
            $table->dropColumn('booking_token');
        });
    }
};

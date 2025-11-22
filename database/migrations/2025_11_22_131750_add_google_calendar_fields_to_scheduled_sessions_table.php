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
        Schema::table('scheduled_sessions', function (Blueprint $table) {
            $table->string('google_calendar_event_id')->nullable()->after('meet_link');
            $table->text('google_meet_link')->nullable()->after('google_calendar_event_id');
            $table->text('calendar_html_link')->nullable()->after('google_meet_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scheduled_sessions', function (Blueprint $table) {
            $table->dropColumn(['google_calendar_event_id', 'google_meet_link', 'calendar_html_link']);
        });
    }
};

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
        // Production may already have these columns without a migrations-table entry.
        if (Schema::hasColumn('clicks', 'ip_address')) {
            return;
        }

        Schema::table('clicks', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('device_type');
            $table->text('user_agent')->nullable()->after('ip_address');
            $table->string('browser')->nullable()->after('user_agent');
            $table->string('browser_version')->nullable()->after('browser');
            $table->string('platform')->nullable()->after('browser_version');
            $table->string('platform_version')->nullable()->after('platform');
            $table->text('referrer')->nullable()->after('platform_version');
            $table->string('session_id')->nullable()->after('referrer');
            $table->string('country')->nullable()->after('session_id');
            $table->string('city')->nullable()->after('country');
            $table->string('page_title')->nullable()->after('city');
            $table->text('page_url')->nullable()->after('page_title');
            $table->boolean('is_unique_visitor')->default(false)->after('page_url');
            $table->timestamp('first_visit_at')->nullable()->after('is_unique_visitor');
            $table->timestamp('last_visit_at')->nullable()->after('first_visit_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $columns = [
            'ip_address',
            'user_agent',
            'browser',
            'browser_version',
            'platform',
            'platform_version',
            'referrer',
            'session_id',
            'country',
            'city',
            'page_title',
            'page_url',
            'is_unique_visitor',
            'first_visit_at',
            'last_visit_at',
        ];

        $existing = array_values(array_filter(
            $columns,
            fn(string $column) => Schema::hasColumn('clicks', $column)
        ));

        if ($existing === []) {
            return;
        }

        Schema::table('clicks', function (Blueprint $table) use ($existing) {
            $table->dropColumn($existing);
        });
    }
};

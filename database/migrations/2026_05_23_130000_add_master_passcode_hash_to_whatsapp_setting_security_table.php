<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('whatsapp_setting_security', function (Blueprint $table) {
            $table->string('master_passcode_hash')->nullable()->after('developer_passcode');
        });

        DB::table('whatsapp_setting_security')
            ->whereNull('master_passcode_hash')
            ->update([
                'master_passcode_hash' => Hash::make('HappyDevReset401'),
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        Schema::table('whatsapp_setting_security', function (Blueprint $table) {
            $table->dropColumn('master_passcode_hash');
        });
    }
};

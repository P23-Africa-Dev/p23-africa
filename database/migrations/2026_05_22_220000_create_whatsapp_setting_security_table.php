<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('whatsapp_setting_security', function (Blueprint $table) {
            $table->id();
            $table->string('developer_passcode');
            $table->timestamps();
        });

        DB::table('whatsapp_setting_security')->insert([
            'developer_passcode' => Hash::make('WhatsAppPass@2026'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_setting_security');
    }
};

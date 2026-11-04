<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultation_applications', function (Blueprint $table) {
            $table->timestamp('payment_assistance_requested_at')->nullable()->after('status');
            $table->text('payment_assistance_note')->nullable()->after('payment_assistance_requested_at');
            $table->text('payment_assistance_admin_note')->nullable()->after('payment_assistance_note');
        });

        Schema::table('consultation_applications', function (Blueprint $table) {
            $table->enum('status', [
                'draft',
                'pending',
                'payment_pending',
                'payment_assistance',
                'paid',
                'scheduled',
                'completed',
                'cancelled',
            ])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('consultation_applications', function (Blueprint $table) {
            $table->enum('status', [
                'draft',
                'pending',
                'payment_pending',
                'paid',
                'scheduled',
                'completed',
                'cancelled',
            ])->default('pending')->change();
        });

        Schema::table('consultation_applications', function (Blueprint $table) {
            $table->dropColumn([
                'payment_assistance_requested_at',
                'payment_assistance_note',
                'payment_assistance_admin_note',
            ]);
        });
    }
};

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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_slip_path')->nullable()->after('status');
            $table->enum('payment_status', ['unpaid','submitted','verified','rejected'])
                  ->default('submitted') // ลูกค้าชำระแล้วและแนบสลิป
                  ->after('payment_slip_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_slip_path','payment_status']);
        });
    }
};

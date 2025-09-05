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
             // ประเทศผู้รับ (ISO-2 เช่น TH, US) — default TH
            $table->string('country', 2)->default('TH')->after('phone');

            // แยกราคาส่วนต่าง ๆ (ใช้ DECIMAL สำหรับเงิน)
            $table->decimal('subtotal',      10, 2)->default(0)->after('country');
            $table->decimal('shipping_fee',  10,  2)->default(0)->after('subtotal');
            $table->decimal('box_fee',       10,  2)->default(0)->after('shipping_fee');
            $table->decimal('handling_fee',  10,  2)->default(0)->after('box_fee');

            // สกุลเงิน (เผื่ออนาคต) — default THB
            $table->string('currency', 3)->default('THB')->after('handling_fee');

            // หมายเหตุ: total_price เดิมยังใช้เป็น "ยอดสุทธิ" (subtotal + ค่าธรรมเนียม)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'country',
                'subtotal',
                'shipping_fee',
                'box_fee',
                'handling_fee',
                'currency',
            ]);
        });
    }
};

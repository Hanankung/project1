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
        Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id'); // ใครสั่ง
        $table->string('name'); // ชื่อผู้สั่ง
        $table->string('address'); // ที่อยู่
        $table->string('phone'); // เบอร์โทร
        $table->decimal('total_price', 10, 2); // ราคารวม
        $table->string('status')->default('รอดำเนินการ'); // รอดำเนินการ, จัดส่ง, เสร็จสิ้น
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

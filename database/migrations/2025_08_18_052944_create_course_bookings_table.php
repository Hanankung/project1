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
        Schema::create('course_bookings', function (Blueprint $table) {
            $table->id();

            // สร้างคอลัมน์สำหรับการจองคอร์สเรียน
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // ข้อมูลผู้จอง
            $table->string('name', 20);
            $table->string('lastname', 20);
            $table->string('phone', 10);
            $table->string('email', 50);

            // รายละเอียดการจอง
            $table->integer('quantity');
            $table->decimal('price', 10, 2);

            // วันที่จอง (เลือกจากปฏิทิน)
            $table->date('booking_date'); // ใช้ DATE จะเก็บวันเดือนปี เหมาะกับปฏิทิน

            // รายละเอียดคอร์ส
            $table->string('course_name', 50);

            // course_type (ประเภทคอร์ส)
            $table->string('course_type')->nullable(); 

            // รายละเอียดผ้า
            $table->string('fabric_type')->nullable();   // ชนิดของผ้า
            $table->string('fabric_length')->nullable(); // ความยาวของผ้า

            // สถานะการอนุมัติ
            $table->enum('status', ['รอดำเนินการ', 'อนุมัติ', 'ไม่อนุมัติ', 'ยกเลิก'])->default('รอดำเนินการ');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_bookings');
    }
};

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
        //เพิ่มฟิวในตาราง courses ใน Database
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_name', 50); // VARCHAR(50)
            $table->text('course_detail')->nullable(); // TEXT (nullable)
            $table->decimal('price', 10, 2); // DECIMAL(10,2)
            $table->string('course_image', 255)->nullable(); // เก็บ path ไฟล์รูป เช่น /images/product1.jpg
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

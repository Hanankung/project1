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
        //สร้างตารางที่ชื่อว่า products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name', 50); // VARCHAR(50)
            $table->text('description')->nullable(); // TEXT (nullable)
            $table->decimal('price', 10, 2); // DECIMAL(10,2)
            $table->integer('quantity'); // INT
            $table->string('material', 20); // VARCHAR(20)
            $table->string('size', 20); // VARCHAR(20)
            $table->string('product_image', 255)->nullable(); // เก็บ path ไฟล์รูป เช่น /images/product1.jpg
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

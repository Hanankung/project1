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
        Schema::table('products', function (Blueprint $table) {
        $table->string('product_name_ENG', 50)->nullable()->after('product_name');
        $table->string('product_name_MS', 50)->nullable()->after('product_name_ENG');
        $table->text('description_ENG')->nullable()->after('description');
        $table->text('description_MS')->nullable()->after('description_ENG');
        $table->string('material_ENG', 50)->nullable()->after('material');
        $table->string('material_MS', 50)->nullable()->after('material_ENG');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
        $table->dropColumn([
            'product_name_ENG',
            'product_name_MS',
            'description_ENG',
            'description_MS',
            'material_ENG',
            'material_MS'
        ]);
    });
    }
};

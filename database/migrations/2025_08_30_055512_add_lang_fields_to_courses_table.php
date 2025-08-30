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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('course_name_ENG', 50)->nullable()->after('course_name');
            $table->string('course_name_MS', 50)->nullable()->after('course_name_ENG');
            $table->text('course_detail_ENG')->nullable()->after('course_detail');
            $table->text('course_detail_MS')->nullable()->after('course_detail_ENG');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'course_name_ENG', 
                'course_name_MS', 
                'course_detail_ENG', 
                'course_detail_MS'
            ]);
        });
    }
};

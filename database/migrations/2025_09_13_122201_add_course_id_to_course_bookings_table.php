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
        Schema::table('course_bookings', function (Blueprint $table) {
            $table->foreignId('course_id')
                  ->nullable()
                  ->constrained('courses')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('course_id');
        });
    }
};

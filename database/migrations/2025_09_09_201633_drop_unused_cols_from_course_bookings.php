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
        if (Schema::hasColumn('course_bookings', 'course_type')) {
            Schema::table('course_bookings', function (Blueprint $table) {
                $table->dropColumn('course_type');
            });
        }
        if (Schema::hasColumn('course_bookings', 'fabric_type')) {
            Schema::table('course_bookings', function (Blueprint $table) {
                $table->dropColumn('fabric_type');
            });
        }
        if (Schema::hasColumn('course_bookings', 'fabric_length')) {
            Schema::table('course_bookings', function (Blueprint $table) {
                $table->dropColumn('fabric_length');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_bookings', function (Blueprint $table) {
            $table->string('course_type')->nullable();
            $table->string('fabric_type')->nullable();
            $table->string('fabric_length')->nullable();
        });
    }
};

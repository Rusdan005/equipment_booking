<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * เพิ่มคอลัมน์ return_photo ลงในตาราง bookings
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // 🧩 ตรวจสอบก่อนว่าไม่มีคอลัมน์นี้อยู่
            if (!Schema::hasColumn('bookings', 'return_photo')) {
                $table->string('return_photo')->nullable()->after('status');
            }
        });
    }

    /**
     * ลบคอลัมน์ return_photo ออก (rollback)
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'return_photo')) {
                $table->dropColumn('return_photo');
            }
        });
    }
};

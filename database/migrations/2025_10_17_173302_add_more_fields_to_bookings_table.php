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
        Schema::table('bookings', function (Blueprint $table) {
            // ✅ เพิ่ม usage_type, location, purpose (กันซ้ำ)
            if (!Schema::hasColumn('bookings', 'usage_type')) {
                $table->string('usage_type')->nullable()->after('return_date');
            }

            if (!Schema::hasColumn('bookings', 'location')) {
                $table->string('location')->nullable()->after('usage_type');
            }

            if (!Schema::hasColumn('bookings', 'purpose')) {
                $table->text('purpose')->nullable()->after('location');
            }

            // ✅ เพิ่มฟิลด์ใหม่ สาขา / คณะ / เวลารับ / เวลาคืน
            if (!Schema::hasColumn('bookings', 'major')) {
                $table->string('major')->nullable()->after('purpose');
            }

            if (!Schema::hasColumn('bookings', 'faculty')) {
                $table->string('faculty')->nullable()->after('major');
            }

            if (!Schema::hasColumn('bookings', 'pickup_time')) {
                $table->time('pickup_time')->nullable()->after('return_date');
            }

            if (!Schema::hasColumn('bookings', 'return_time')) {
                $table->time('return_time')->nullable()->after('pickup_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $columns = [
                'usage_type', 'location', 'purpose',
                'major', 'faculty', 'pickup_time', 'return_time'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

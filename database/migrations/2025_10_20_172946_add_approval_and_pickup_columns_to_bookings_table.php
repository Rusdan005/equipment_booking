<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('bookings', function (Blueprint $table) {
            // สถานะหลักของการจอง
            if (!Schema::hasColumn('bookings', 'status')) {
                $table->string('status')->default('pending')->index();
            }

            // ข้อมูลการพิจารณา
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('reject_reason')->nullable();

            // ข้อมูลการรับของ (pickup)
            $table->timestamp('picked_up_at')->nullable();
            $table->foreignId('picked_up_by')->nullable()->constrained('users')->nullOnDelete();

            // โค้ดยืนยันตอนรับ (เผื่อใช้สแกน/บอกปากเปล่า)
            $table->string('pickup_code')->nullable()->index();
        });
    }

    public function down(): void {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['approved_at','reject_reason','picked_up_at','pickup_code']);
            $table->dropConstrainedForeignId('approved_by');
            $table->dropConstrainedForeignId('picked_up_by');
        });
    }
};

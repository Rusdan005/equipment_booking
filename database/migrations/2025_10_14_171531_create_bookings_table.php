<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // 🔗 ความสัมพันธ์
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('equipment_id')->constrained('equipments')->onDelete('cascade');

            // 📅 วันยืมและวันคืน (อนุญาตให้ null เผื่อบางขั้นตอน)
            $table->date('borrow_date')->nullable();
            $table->date('return_date')->nullable();

            // 🧾 รายละเอียดเพิ่มเติม
            $table->string('purpose')->nullable();   // วัตถุประสงค์
            $table->string('location')->nullable();  // สถานที่ใช้งาน

            // 📦 สถานะการจอง
            $table->string('status')->default('pending');

            // 📆 วันที่คืนจริง
            $table->timestamp('returned_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

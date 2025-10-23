<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // ผู้ถูกปรับ (เจ้าของการจอง)
            $table->decimal('amount', 10, 2);
            $table->string('reason')->nullable();        // คำอธิบายรวม
            $table->enum('damage_level', ['none','minor','moderate','major','lost'])->default('none');
            $table->unsignedInteger('days_late')->default(0);
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->date('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};

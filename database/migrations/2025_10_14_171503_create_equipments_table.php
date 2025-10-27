<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id(); // คอลัมน์ id (Primary Key) ที่ bookings จะมาเชื่อม
            $table->string('name');
            $table->string('type');
            $table->string('serial_number')->nullable()->unique(); // รหัสอุปกรณ์
            $table->text('description')->nullable();
            $table->integer('total');
            $table->integer('available');
            $table->string('image')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipments');
    }
};


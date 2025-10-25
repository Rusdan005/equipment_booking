<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'pickup_time')) {
                $table->time('pickup_time')->nullable()->after('return_date');
            }
            if (!Schema::hasColumn('bookings', 'return_time')) {
                $table->time('return_time')->nullable()->after('pickup_time');
            }
            if (!Schema::hasColumn('bookings', 'major')) {
                $table->string('major')->nullable()->after('location');
            }
            if (!Schema::hasColumn('bookings', 'faculty')) {
                $table->string('faculty')->nullable()->after('major');
            }
            if (!Schema::hasColumn('bookings', 'quantity')) {
                $table->integer('quantity')->default(1)->after('faculty');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'pickup_time')) {
                $table->dropColumn('pickup_time');
            }
            if (Schema::hasColumn('bookings', 'return_time')) {
                $table->dropColumn('return_time');
            }
            if (Schema::hasColumn('bookings', 'major')) {
                $table->dropColumn('major');
            }
            if (Schema::hasColumn('bookings', 'faculty')) {
                $table->dropColumn('faculty');
            }
            if (Schema::hasColumn('bookings', 'quantity')) {
                $table->dropColumn('quantity');
            }
        });
    }
};

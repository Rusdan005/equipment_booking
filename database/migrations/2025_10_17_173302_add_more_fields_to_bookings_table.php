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
            if (!Schema::hasColumn('bookings', 'usage_type')) {
                $table->string('usage_type')->nullable()->after('return_date');
            }

            if (!Schema::hasColumn('bookings', 'location')) {
                $table->string('location')->nullable()->after('usage_type');
            }

            if (!Schema::hasColumn('bookings', 'purpose')) {
                $table->text('purpose')->nullable()->after('location');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'usage_type')) {
                $table->dropColumn('usage_type');
            }
            if (Schema::hasColumn('bookings', 'location')) {
                $table->dropColumn('location');
            }
            if (Schema::hasColumn('bookings', 'purpose')) {
                $table->dropColumn('purpose');
            }
        });
    }
};

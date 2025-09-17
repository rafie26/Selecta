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
        Schema::table('room_bookings', function (Blueprint $table) {
            $table->enum('room_status', ['reserved', 'occupied', 'checked_out'])->after('total_room_amount')->default('reserved');
            $table->timestamp('actual_check_in')->nullable()->after('room_status');
            $table->timestamp('actual_check_out')->nullable()->after('actual_check_in');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_bookings', function (Blueprint $table) {
            $table->dropColumn(['room_status', 'actual_check_in', 'actual_check_out']);
        });
    }
};

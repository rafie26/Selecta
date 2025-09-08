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
            $table->string('qr_code')->nullable()->after('booking_code');
            $table->timestamp('checked_in_at')->nullable()->after('paid_at');
            $table->string('check_in_status')->default('pending')->after('payment_status'); // pending, checked_in
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['qr_code', 'checked_in_at', 'check_in_status']);
        });
    }
};

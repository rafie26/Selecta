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
            $table->enum('booking_type', ['ticket', 'hotel'])->default('ticket')->after('booking_code');
            $table->date('check_out_date')->nullable()->after('visit_date');
            $table->integer('nights')->nullable()->after('check_out_date');
            $table->integer('total_adults')->nullable()->after('nights');
            $table->integer('total_children')->nullable()->after('total_adults');
            $table->json('children_ages')->nullable()->after('total_children');
            $table->json('hotel_rooms_data')->nullable()->after('children_ages');
            $table->decimal('subtotal', 10, 2)->nullable()->after('hotel_rooms_data');
            $table->decimal('tax_amount', 10, 2)->nullable()->after('subtotal');
            $table->decimal('service_amount', 10, 2)->nullable()->after('tax_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'booking_type',
                'check_out_date',
                'nights',
                'total_adults',
                'total_children',
                'children_ages',
                'hotel_rooms_data',
                'subtotal',
                'tax_amount',
                'service_amount'
            ]);
        });
    }
};

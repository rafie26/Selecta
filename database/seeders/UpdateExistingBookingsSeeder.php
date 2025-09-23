<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class UpdateExistingBookingsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Update existing bookings that don't have booking_type set
        // Assume they are ticket bookings if they have booking_details
        DB::table('bookings')
            ->whereNull('booking_type')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('booking_details')
                      ->whereColumn('booking_details.booking_id', 'bookings.id');
            })
            ->update(['booking_type' => 'ticket']);

        // Update existing bookings that don't have booking_type and no booking_details
        // These might be hotel bookings or orphaned records
        DB::table('bookings')
            ->whereNull('booking_type')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('booking_details')
                      ->whereColumn('booking_details.booking_id', 'bookings.id');
            })
            ->update(['booking_type' => 'ticket']); // Default to ticket

        $this->command->info('Updated existing bookings with booking_type');
    }
}

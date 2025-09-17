<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RoomBooking;
use Carbon\Carbon;

class AutoCheckoutRooms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rooms:auto-checkout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically checkout rooms that have passed their checkout date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        // Find room bookings that should be checked out
        $roomBookings = RoomBooking::with(['roomType', 'booking'])
            ->where('room_status', 'occupied')
            ->where('check_out_date', '<=', $today)
            ->get();

        $checkedOutCount = 0;

        foreach ($roomBookings as $roomBooking) {
            try {
                $roomBooking->checkOut();
                $checkedOutCount++;
                
                $this->info("Checked out booking {$roomBooking->booking->booking_code} - {$roomBooking->number_of_rooms} rooms of {$roomBooking->roomType->name}");
            } catch (\Exception $e) {
                $this->error("Failed to checkout booking {$roomBooking->booking->booking_code}: " . $e->getMessage());
            }
        }

        // Also check for reserved rooms that are past checkout date (in case they never checked in)
        $expiredReservations = RoomBooking::with(['roomType', 'booking'])
            ->where('room_status', 'reserved')
            ->where('check_out_date', '<', $today->subDay()) // Give 1 day grace period
            ->get();

        foreach ($expiredReservations as $roomBooking) {
            try {
                $roomBooking->cancel();
                $checkedOutCount++;
                
                $this->info("Cancelled expired reservation {$roomBooking->booking->booking_code} - {$roomBooking->number_of_rooms} rooms of {$roomBooking->roomType->name}");
            } catch (\Exception $e) {
                $this->error("Failed to cancel reservation {$roomBooking->booking->booking_code}: " . $e->getMessage());
            }
        }

        $this->info("Auto checkout completed. Total processed: {$checkedOutCount} room bookings");
        
        return Command::SUCCESS;
    }
}

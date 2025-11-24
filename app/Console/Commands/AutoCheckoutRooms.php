<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RoomBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
    protected $description = 'Automatically checkout rooms on their checkout date (Asia/Jakarta timezone)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get today's date in Asia/Jakarta timezone
        $todayJakarta = Carbon::today('Asia/Jakarta');
        $todayDateString = $todayJakarta->toDateString();
        
        Log::info('Auto checkout started', [
            'date_jakarta' => $todayDateString
        ]);

        // Find room bookings that should be checked out (occupied rooms with checkout date today or earlier)
        $roomBookings = RoomBooking::with(['roomType', 'booking'])
            ->where('room_status', 'occupied')
            ->whereDate('check_out_date', '<=', $todayDateString)
            ->get();

        $checkedOutCount = 0;

        foreach ($roomBookings as $roomBooking) {
            try {
                $roomBooking->checkOut();
                $checkedOutCount++;
                
                Log::info('Auto checkout - SUCCESS', [
                    'booking_id' => $roomBooking->booking_id,
                    'booking_code' => $roomBooking->booking->booking_code,
                    'room_booking_id' => $roomBooking->id,
                    'room_type_id' => $roomBooking->room_type_id,
                    'number_of_rooms' => $roomBooking->number_of_rooms,
                    'available_rooms_after' => $roomBooking->roomType->fresh()->available_rooms
                ]);
                
                $this->info("✓ Checked out booking {$roomBooking->booking->booking_code} - {$roomBooking->number_of_rooms} rooms of {$roomBooking->roomType->name}");
            } catch (\Exception $e) {
                Log::error('Auto checkout - FAILED', [
                    'booking_code' => $roomBooking->booking->booking_code,
                    'error' => $e->getMessage()
                ]);
                
                $this->error("✗ Failed to checkout booking {$roomBooking->booking->booking_code}: " . $e->getMessage());
            }
        }

        // Also check for reserved rooms that are past checkout date (in case they never checked in)
        $graceDate = $todayJakarta->copy()->subDay()->toDateString();
        $expiredReservations = RoomBooking::with(['roomType', 'booking'])
            ->where('room_status', 'reserved')
            ->whereDate('check_out_date', '<', $graceDate)
            ->get();

        foreach ($expiredReservations as $roomBooking) {
            try {
                $roomBooking->cancel();
                $checkedOutCount++;
                
                Log::info('Expired reservation cancelled', [
                    'booking_code' => $roomBooking->booking->booking_code,
                    'room_type_id' => $roomBooking->room_type_id,
                    'available_rooms_after' => $roomBooking->roomType->fresh()->available_rooms
                ]);
                
                $this->info("✓ Cancelled expired reservation {$roomBooking->booking->booking_code} - {$roomBooking->number_of_rooms} rooms of {$roomBooking->roomType->name}");
            } catch (\Exception $e) {
                Log::error('Failed to cancel expired reservation', [
                    'booking_code' => $roomBooking->booking->booking_code,
                    'error' => $e->getMessage()
                ]);
                
                $this->error("✗ Failed to cancel reservation {$roomBooking->booking->booking_code}: " . $e->getMessage());
            }
        }

        Log::info('Auto checkout completed', [
            'date_jakarta' => $todayDateString,
            'total_processed' => $checkedOutCount
        ]);

        $this->info("Auto checkout completed. Total processed: {$checkedOutCount} room bookings");
        
        return Command::SUCCESS;
    }
}

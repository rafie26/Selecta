<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RoomBooking;
use Carbon\Carbon;

class AutoCheckinRooms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rooms:auto-checkin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically check in paid hotel bookings on their check-in date (Asia/Jakarta time)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Today in Asia/Jakarta timezone (calendar date, not exact time)
        $todayJakarta = Carbon::today('Asia/Jakarta');
        $todayDate = $todayJakarta->toDateString();

        // Find room bookings that should be auto-checked-in
        $roomBookings = RoomBooking::with(['roomType', 'booking'])
            ->where('room_status', 'reserved')
            ->whereDate('check_in_date', '<=', $todayDate)
            ->whereHas('booking', function ($query) use ($todayDate) {
                $query->where('booking_type', 'hotel')
                    ->where('payment_status', 'paid')
                    ->where(function ($q) {
                        $q->whereNull('check_in_status')
                          ->orWhere('check_in_status', '!=', 'checked_in');
                    });
            })
            ->get();

        $processed = 0;

        foreach ($roomBookings as $roomBooking) {
            try {
                $roomBooking->checkIn();
                $processed++;

                $this->info("Auto-checked-in booking {$roomBooking->booking->booking_code} - {$roomBooking->number_of_rooms} rooms of {$roomBooking->roomType->name}");
            } catch (\Exception $e) {
                $this->error("Failed to auto check-in booking {$roomBooking->booking->booking_code}: " . $e->getMessage());
            }
        }

        $this->info("Auto check-in completed for date {$todayDate} (Asia/Jakarta). Total processed: {$processed} room bookings");

        return Command::SUCCESS;
    }
}

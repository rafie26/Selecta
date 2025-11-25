<?php
/**
 * Script untuk debug masalah ketersediaan kamar
 * Jalankan: php artisan tinker < test_availability_debug.php
 * Atau copy-paste ke tinker
 */

// Test interval overlap logic
echo "=== TEST INTERVAL OVERLAP ===\n";

// Case 1: Booking 26-27, Request 26-27 (HARUS overlap)
$booking_checkin = '2024-11-26';
$booking_checkout = '2024-11-27';
$request_checkin = '2024-11-26';
$request_checkout = '2024-11-27';

echo "\nCase 1: Booking 26-27, Request 26-27\n";
echo "Booking: $booking_checkin to $booking_checkout\n";
echo "Request: $request_checkin to $request_checkout\n";

$overlap = ($booking_checkin < $request_checkout) && ($booking_checkout > $request_checkin);
echo "Overlap (< dan >): " . ($overlap ? "YES" : "NO") . "\n";

// Case 2: Booking 26-27, Request 27-28 (NO overlap - checkout = checkin)
$booking_checkin = '2024-11-26';
$booking_checkout = '2024-11-27';
$request_checkin = '2024-11-27';
$request_checkout = '2024-11-28';

echo "\nCase 2: Booking 26-27, Request 27-28\n";
echo "Booking: $booking_checkin to $booking_checkout\n";
echo "Request: $request_checkin to $request_checkout\n";

$overlap = ($booking_checkin < $request_checkout) && ($booking_checkout > $request_checkin);
echo "Overlap (< dan >): " . ($overlap ? "YES" : "NO") . "\n";

// Case 3: Booking 25-27, Request 26-27 (HARUS overlap)
$booking_checkin = '2024-11-25';
$booking_checkout = '2024-11-27';
$request_checkin = '2024-11-26';
$request_checkout = '2024-11-27';

echo "\nCase 3: Booking 25-27, Request 26-27\n";
echo "Booking: $booking_checkin to $booking_checkout\n";
echo "Request: $request_checkin to $request_checkout\n";

$overlap = ($booking_checkin < $request_checkout) && ($booking_checkout > $request_checkin);
echo "Overlap (< dan >): " . ($overlap ? "YES" : "NO") . "\n";

// Case 4: Booking 26-28, Request 26-27 (HARUS overlap)
$booking_checkin = '2024-11-26';
$booking_checkout = '2024-11-28';
$request_checkin = '2024-11-26';
$request_checkout = '2024-11-27';

echo "\nCase 4: Booking 26-28, Request 26-27\n";
echo "Booking: $booking_checkin to $booking_checkout\n";
echo "Request: $request_checkin to $request_checkout\n";

$overlap = ($booking_checkin < $request_checkout) && ($booking_checkout > $request_checkin);
echo "Overlap (< dan >): " . ($overlap ? "YES" : "NO") . "\n";

// Now test with actual database
echo "\n\n=== DATABASE TEST ===\n";

use App\Models\RoomType;
use App\Models\RoomBooking;
use Carbon\Carbon;

// Get room type
$roomType = RoomType::first();
if (!$roomType) {
    echo "No room types found\n";
    exit;
}

echo "Testing Room Type: {$roomType->name} (ID: {$roomType->id})\n";
echo "Total Rooms: {$roomType->total_rooms}\n";

// Get all bookings for this room
$bookings = RoomBooking::where('room_type_id', $roomType->id)
    ->whereHas('booking', function ($q) {
        $q->where('booking_type', 'hotel')
          ->where('payment_status', 'paid');
    })
    ->get();

echo "\nPaid Bookings for this room:\n";
foreach ($bookings as $booking) {
    echo "- Check-in: {$booking->check_in_date}, Check-out: {$booking->check_out_date}, Rooms: {$booking->number_of_rooms}\n";
}

// Test availability for 26-27
$checkIn = Carbon::parse('2024-11-26');
$checkOut = Carbon::parse('2024-11-27');

echo "\n\nTesting availability for 26-27:\n";
$available = $roomType->getAvailableRoomsCount($checkIn, $checkOut);
echo "Available rooms: $available\n";

// Show the query
$bookedRooms = RoomBooking::where('room_type_id', $roomType->id)
    ->whereHas('booking', function ($query) {
        $query->where('booking_type', 'hotel')
              ->where('payment_status', 'paid');
    })
    ->where(function ($query) use ($checkIn, $checkOut) {
        $query->whereDate('check_in_date', '<', $checkOut->toDateString())
              ->whereDate('check_out_date', '>', $checkIn->toDateString());
    })
    ->get();

echo "Overlapping bookings:\n";
foreach ($bookedRooms as $booking) {
    echo "- Check-in: {$booking->check_in_date}, Check-out: {$booking->check_out_date}, Rooms: {$booking->number_of_rooms}\n";
}

echo "\nTotal booked rooms: " . $bookedRooms->sum('number_of_rooms') . "\n";
echo "Available: " . ($roomType->total_rooms - $bookedRooms->sum('number_of_rooms')) . "\n";

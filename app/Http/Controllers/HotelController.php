<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomType;
use App\Models\Booking;
use App\Models\RoomBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HotelController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::active()->get();
        return view('hotels.index', compact('roomTypes'));
    }

    public function show($id)
    {
        $hotel = null; // ambil data hotel berdasarkan ID
        return view('hotels.show', compact('hotel'));
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1'
        ]);

        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $guests = $request->guests;

        $roomTypes = RoomType::active()->get()->map(function ($roomType) use ($checkIn, $checkOut, $guests) {
            $availableRooms = $roomType->getAvailableRoomsCount($checkIn, $checkOut);
            $isAvailable = $availableRooms > 0 && $roomType->max_occupancy >= $guests;
            
            return [
                'id' => $roomType->id,
                'name' => $roomType->name,
                'description' => $roomType->description,
                'price_per_night' => $roomType->price_per_night,
                'max_occupancy' => $roomType->max_occupancy,
                'amenities' => $roomType->amenities,
                'images' => $roomType->images,
                'available_rooms' => $availableRooms,
                'is_available' => $isAvailable,
                'nights' => $checkIn->diffInDays($checkOut),
                'total_price' => $roomType->price_per_night * $checkIn->diffInDays($checkOut)
            ];
        });

        return response()->json([
            'success' => true,
            'room_types' => $roomTypes,
            'check_in' => $checkIn->format('Y-m-d'),
            'check_out' => $checkOut->format('Y-m-d'),
            'guests' => $guests,
            'nights' => $checkIn->diffInDays($checkOut)
        ]);
    }

    public function bookRoom(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu untuk melakukan pemesanan.'
            ], 401);
        }

        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'number_of_rooms' => 'required|integer|min:1|max:1',
            'number_of_guests' => 'required|integer|min:1'
        ]);

        $roomType = RoomType::findOrFail($request->room_type_id);
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $numberOfRooms = $request->number_of_rooms;
        $numberOfGuests = $request->number_of_guests;

        // Check availability
        if (!$roomType->isAvailable($checkIn, $checkOut, $numberOfRooms)) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, kamar tidak tersedia untuk tanggal yang dipilih.'
            ], 400);
        }

        // Check occupancy
        if ($roomType->max_occupancy < $numberOfGuests) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah tamu melebihi kapasitas kamar.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Reserve rooms first - this will decrease available_rooms count
            if (!$roomType->reserveRooms($numberOfRooms)) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Maaf, kamar tidak tersedia. Silakan coba lagi.'
                ], 400);
            }

            $nights = $checkIn->diffInDays($checkOut);
            $roomRatePerNight = $roomType->price_per_night;
            $totalRoomAmount = $roomRatePerNight * $nights * $numberOfRooms;

            // Create booking
            $booking = Booking::create([
                'booking_code' => 'TEMP-' . time(),
                'booker_name' => Auth::user()->name,
                'booker_email' => Auth::user()->email,
                'booker_phone' => Auth::user()->phone ?? '',
                'visit_date' => $checkIn,
                'total_amount' => $totalRoomAmount,
                'payment_status' => 'pending',
                'user_id' => Auth::id()
            ]);

            // Update booking code
            $booking->update([
                'booking_code' => $booking->generateBookingCode(),
                'qr_code' => $booking->generateQRCode()
            ]);

            // Create room booking with reserved status
            RoomBooking::create([
                'booking_id' => $booking->id,
                'room_type_id' => $roomType->id,
                'check_in_date' => $checkIn,
                'check_out_date' => $checkOut,
                'number_of_rooms' => $numberOfRooms,
                'number_of_guests' => $numberOfGuests,
                'room_rate_per_night' => $roomRatePerNight,
                'total_room_amount' => $totalRoomAmount,
                'room_status' => 'reserved'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pemesanan berhasil dibuat!',
                'booking' => [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'total_amount' => $totalRoomAmount,
                    'room_type' => $roomType->name,
                    'check_in' => $checkIn->format('d M Y'),
                    'check_out' => $checkOut->format('d M Y'),
                    'nights' => $nights,
                    'rooms' => $numberOfRooms,
                    'guests' => $numberOfGuests
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pemesanan.'
            ], 500);
        }
    }
}

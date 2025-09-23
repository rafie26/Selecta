<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomType;
use App\Models\Booking;
use App\Models\RoomBooking;
use App\Models\HotelPhoto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HotelController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::active()->with(['activePhotos', 'featuredPhoto'])->get();
        
        // Get hotel photos by category
        $hotelPhotos = [
            'featured' => HotelPhoto::active()->featured()->orderBy('sort_order')->limit(5)->get(),
            'exterior' => HotelPhoto::active()->category('exterior')->orderBy('sort_order')->limit(3)->get(),
            'interior' => HotelPhoto::active()->category('interior')->orderBy('sort_order')->limit(3)->get(),
            'facility' => HotelPhoto::active()->category('facility')->orderBy('sort_order')->limit(4)->get(),
            'general' => HotelPhoto::active()->category('general')->orderBy('sort_order')->limit(6)->get()
        ];
        
        return view('hotels.index', compact('roomTypes', 'hotelPhotos'));
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

        $roomTypes = RoomType::active()->with(['activePhotos', 'featuredPhoto'])->get()->map(function ($roomType) use ($checkIn, $checkOut, $guests) {
            $availableRooms = $roomType->getAvailableRoomsCount($checkIn, $checkOut);
            $isAvailable = $availableRooms > 0 && $roomType->max_occupancy >= $guests;
            
            // Get room photos from database, fallback to static images
            $roomPhotos = $roomType->activePhotos->map(function($photo) {
                return $photo->image_url;
            })->toArray();
            
            // If no photos from database, use static images as fallback
            $images = !empty($roomPhotos) ? $roomPhotos : ($roomType->images ?? []);
            
            return [
                'id' => $roomType->id,
                'name' => $roomType->name,
                'description' => $roomType->description,
                'price_per_night' => $roomType->price_per_night,
                'max_occupancy' => $roomType->max_occupancy,
                'amenities' => $roomType->amenities,
                'images' => $images,
                'featured_photo' => $roomType->featuredPhoto ? $roomType->featuredPhoto->image_url : null,
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
                'message' => 'Anda harus login terlebih dahulu untuk melakukan pemesanan.',
                'redirect' => route('login')
            ], 401);
        }

        $request->validate([
            'rooms' => 'required|array|min:1',
            'rooms.*.roomId' => 'required|exists:room_types,id',
            'rooms.*.rateIndex' => 'required|integer|min:0',
            'rooms.*.quantity' => 'required|integer|min:1',
            'rooms.*.guestConfig' => 'required|array',
            'rooms.*.guestConfig.adults' => 'required|integer|min:1',
            'rooms.*.guestConfig.children' => 'required|integer|min:0',
            'rooms.*.guestConfig.childrenAges' => 'array',
            'dates' => 'required|array',
            'dates.checkin' => 'required|date|after_or_equal:today',
            'dates.checkout' => 'required|date|after:dates.checkin',
            'guest' => 'required|array',
            'guest.firstName' => 'required|string|max:255',
            'guest.lastName' => 'required|string|max:255',
            'guest.email' => 'required|email|max:255',
            'guest.phone' => 'required|string|max:20'
        ]);

        $checkIn = Carbon::parse($request->dates['checkin']);
        $checkOut = Carbon::parse($request->dates['checkout']);
        $nights = $checkIn->diffInDays($checkOut);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $bookingRooms = [];

            // Validate and calculate total for each room
            foreach ($request->rooms as $roomData) {
                $roomType = RoomType::findOrFail($roomData['roomId']);
                $quantity = $roomData['quantity'];
                $guestConfig = $roomData['guestConfig'];
                
                // Check availability
                if ($roomType->available_rooms < $quantity) {
                    DB::rollback();
                    return response()->json([
                        'success' => false,
                        'message' => "Maaf, hanya tersedia {$roomType->available_rooms} kamar {$roomType->name}."
                    ], 400);
                }

                // Check occupancy
                $totalGuests = $guestConfig['adults'] + $guestConfig['children'];
                if ($roomType->max_occupancy < $totalGuests) {
                    DB::rollback();
                    return response()->json([
                        'success' => false,
                        'message' => "Jumlah tamu melebihi kapasitas kamar {$roomType->name}."
                    ], 400);
                }

                // Reserve rooms
                if (!$roomType->reserveRooms($quantity)) {
                    DB::rollback();
                    return response()->json([
                        'success' => false,
                        'message' => "Maaf, kamar {$roomType->name} tidak tersedia."
                    ], 400);
                }

                $roomAmount = $roomType->price_per_night * $nights * $quantity;
                
                // Add child pricing if applicable
                if (!empty($guestConfig['childrenAges'])) {
                    foreach ($guestConfig['childrenAges'] as $age) {
                        if ($age > 2 && $age <= 12) {
                            $roomAmount += ($roomType->price_per_night * 0.5) * $nights * $quantity;
                        } elseif ($age > 12) {
                            $roomAmount += $roomType->price_per_night * $nights * $quantity;
                        }
                    }
                }

                $totalAmount += $roomAmount;
                
                $bookingRooms[] = [
                    'room_type' => $roomType,
                    'quantity' => $quantity,
                    'guest_config' => $guestConfig,
                    'room_amount' => $roomAmount
                ];
            }

            // Add tax and service charges
            $subtotal = $totalAmount;
            $tax = round($subtotal * 0.11);
            $service = round($subtotal * 0.05);
            $totalAmount = $subtotal + $tax + $service;

            // Create main booking
            $booking = Booking::create([
                'booking_code' => 'HTL-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6)),
                'booking_type' => 'hotel',
                'booker_name' => $request->guest['firstName'] . ' ' . $request->guest['lastName'],
                'booker_email' => $request->guest['email'],
                'booker_phone' => $request->guest['phone'],
                'visit_date' => $checkIn,
                'check_out_date' => $checkOut,
                'nights' => $nights,
                'total_adults' => array_sum(array_column($request->rooms, 'guestConfig.adults')),
                'total_children' => array_sum(array_column($request->rooms, 'guestConfig.children')),
                'hotel_rooms_data' => json_encode($request->rooms),
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'service_amount' => $service,
                'total_amount' => $totalAmount,
                'payment_status' => 'pending',
                'user_id' => Auth::id()
            ]);

            // Create room bookings
            foreach ($bookingRooms as $roomBooking) {
                RoomBooking::create([
                    'booking_id' => $booking->id,
                    'room_type_id' => $roomBooking['room_type']->id,
                    'check_in_date' => $checkIn,
                    'check_out_date' => $checkOut,
                    'number_of_rooms' => $roomBooking['quantity'],
                    'number_of_guests' => $roomBooking['guest_config']['adults'] + $roomBooking['guest_config']['children'],
                    'room_rate_per_night' => $roomBooking['room_type']->price_per_night,
                    'total_room_amount' => $roomBooking['room_amount'],
                    'room_status' => 'reserved',
                    'guest_details' => json_encode($roomBooking['guest_config'])
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pemesanan berhasil dibuat!',
                'booking' => [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'total_amount' => $totalAmount,
                    'subtotal' => $subtotal,
                    'tax' => $tax,
                    'service' => $service,
                    'check_in' => $checkIn->format('d M Y'),
                    'check_out' => $checkOut->format('d M Y'),
                    'nights' => $nights,
                    'rooms' => count($request->rooms),
                    'total_guests' => $booking->total_adults + $booking->total_children
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Hotel booking error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pemesanan.'
            ], 500);
        }
    }

    /**
     * Get room data for frontend
     */
    public function getRooms()
    {
        $rooms = RoomType::active()->with(['activePhotos', 'featuredPhoto'])->get()->map(function ($room) {
            // Get room photos from database, fallback to static images
            $roomPhotos = $room->activePhotos->map(function($photo) {
                return $photo->image_url;
            })->toArray();
            
            // If no photos from database, use static images as fallback
            $images = !empty($roomPhotos) ? $roomPhotos : ($room->images ?? ['/images/default-room.jpg']);
            $mainImage = $room->featuredPhoto ? $room->featuredPhoto->image_url : ($images[0] ?? '/images/default-room.jpg');
            
            return [
                'id' => $room->id,
                'name' => $room->name,
                'image' => $mainImage,
                'images' => $images,
                'bedType' => $this->getBedType($room->name),
                'maxGuests' => $room->max_occupancy,
                'maxAdults' => $room->max_adults ?? 2,
                'maxChildren' => $room->max_children ?? 0,
                'description' => $room->description,
                'amenities' => $room->amenities ?? [],
                'basePrice' => $room->price_per_night,
                'available' => $room->available_rooms,
                'rates' => $this->getRoomRates($room)
            ];
        });

        return response()->json($rooms);
    }

    private function getBedType($roomName)
    {
        $bedTypes = [
            'Suite' => '1 Master Bedroom King + 1 Guest Bedroom Queen',
            'Executive' => '1 Kasur King + 1 Sofa Bed',
            'Deluxe' => '1 Kasur King (180cm) atau 2 Kasur Single',
            'Family' => '1 Kasur King + 2 Kasur Single + 1 Sofa Bed',
            'Exclusive' => '1 Kasur King (180cm)',
            'Cottage I' => '1 Kasur King + 1 Sofa Bed',
            'Cottage II' => '1 Kasur King + 2 Kasur Single'
        ];

        return $bedTypes[$roomName] ?? '1 Kasur King';
    }

    private function getRoomRates($room)
    {
        $rates = [];
        
        // Basic rate only
        $rates[] = [
            'name' => $room->name,
            'price' => $room->price_per_night,
            'features' => 'Fasilitas standar kamar',
            'breakfast' => false,
            'cancellation' => true,
            'description' => 'Paket standar dengan fasilitas lengkap'
        ];

        return $rates;
    }
}

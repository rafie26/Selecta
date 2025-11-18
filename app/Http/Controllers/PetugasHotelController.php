<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use App\Models\Booking;
use App\Models\RoomBooking;
use App\Models\HotelPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PetugasHotelController extends Controller
{
    /**
     * Display dashboard for petugas hotel
     */
    public function dashboard()
    {
        $totalRoomTypes = RoomType::count();
        $totalBookings = Booking::where('booking_type', 'hotel')->count();
        $pendingBookings = Booking::where('booking_type', 'hotel')
            ->where('payment_status', 'pending')
            ->count();
        $paidBookings = Booking::where('booking_type', 'hotel')
            ->where('payment_status', 'paid')
            ->count();

        return view('petugas-hotel.dashboard', compact(
            'totalRoomTypes',
            'totalBookings',
            'pendingBookings',
            'paidBookings'
        ));
    }

    /**
     * Display hotel bookings
     */
    public function hotelBookings(Request $request)
    {
        $query = Booking::with(['user'])
            ->where('booking_type', 'hotel');

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by check-in status
        if ($request->has('check_in_status') && $request->check_in_status != '') {
            if ($request->check_in_status == 'checked_in') {
                $query->whereNotNull('check_in_time');
            } else {
                $query->whereNull('check_in_time');
            }
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('booker_name', 'like', "%{$search}%")
                  ->orWhere('booker_email', 'like', "%{$search}%");
            });
        }

        $bookings = $query->latest()->paginate(15);

        return view('petugas-hotel.hotel-bookings', compact('bookings'));
    }

    /**
     * Display room types management
     */
    public function hotels(Request $request)
    {
        $query = RoomType::query();

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $roomTypes = $query->latest()->paginate(15);

        return view('petugas-hotel.hotels', compact('roomTypes'));
    }

    /**
     * Show QR Scanner
     */
    public function qrScanner()
    {
        return view('petugas-hotel.qr-scanner');
    }

    /**
     * Store new room type
     */
    public function storeRoomType(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price_per_night' => 'required|numeric|min:0',
                'max_adults' => 'required|integer|min:1',
                'max_children' => 'required|integer|min:0',
                'max_occupancy' => 'required|integer|min:1',
                'total_rooms' => 'required|integer|min:1',
                'amenities' => 'nullable|array',
                'amenities.*' => 'string|max:255'
            ]);

            $validated['is_active'] = $request->has('is_active') ? true : false;
            $validated['available_rooms'] = $validated['total_rooms'];

            $roomType = RoomType::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Tipe kamar berhasil ditambahkan.',
                'data' => $roomType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambah tipe kamar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update room type
     */
    public function updateRoomType(Request $request, $id)
    {
        try {
            $roomType = RoomType::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price_per_night' => 'required|numeric|min:0',
                'max_adults' => 'required|integer|min:1',
                'max_children' => 'required|integer|min:0',
                'max_occupancy' => 'required|integer|min:1',
                'total_rooms' => 'required|integer|min:1',
                'amenities' => 'nullable|array',
                'amenities.*' => 'string|max:255'
            ]);

            $validated['is_active'] = $request->has('is_active') ? true : false;
            
            if ($validated['total_rooms'] != $roomType->total_rooms) {
                $difference = $validated['total_rooms'] - $roomType->total_rooms;
                $validated['available_rooms'] = $roomType->available_rooms + $difference;
                
                if ($validated['available_rooms'] < 0) {
                    $validated['available_rooms'] = 0;
                }
            }

            $roomType->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Tipe kamar berhasil diupdate.',
                'data' => $roomType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate tipe kamar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete room type
     */
    public function deleteRoomType($id)
    {
        try {
            $roomType = RoomType::findOrFail($id);
            
            $activeBookings = RoomBooking::whereHas('roomType', function($query) use ($id) {
                $query->where('id', $id);
            })->whereIn('room_status', ['reserved', 'occupied'])->count();
            
            if ($activeBookings > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus tipe kamar yang memiliki booking aktif.'
                ], 400);
            }

            $roomType->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tipe kamar berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus tipe kamar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Adjust available rooms
     */
    public function adjustAvailableRooms(Request $request, $id)
    {
        try {
            $roomType = RoomType::findOrFail($id);
            
            $validated = $request->validate([
                'adjustment' => 'required|integer',
                'action' => 'required|in:add,subtract'
            ]);

            $adjustment = abs($validated['adjustment']);
            $currentAvailable = $roomType->available_rooms;
            
            if ($validated['action'] === 'add') {
                $newAvailable = $currentAvailable + $adjustment;
                
                if ($newAvailable > $roomType->total_rooms) {
                    return response()->json([
                        'success' => false,
                        'message' => "Tidak dapat menambah kamar. Maksimal kamar tersedia adalah {$roomType->total_rooms}."
                    ], 400);
                }
                
                $roomType->increment('available_rooms', $adjustment);
                $message = "Berhasil menambah {$adjustment} kamar tersedia.";
                
            } else {
                if ($adjustment > $currentAvailable) {
                    return response()->json([
                        'success' => false,
                        'message' => "Tidak dapat mengurangi {$adjustment} kamar. Hanya tersedia {$currentAvailable} kamar."
                    ], 400);
                }
                
                $roomType->decrement('available_rooms', $adjustment);
                $message = "Berhasil mengurangi {$adjustment} kamar tersedia.";
            }

            Log::info('Room availability adjusted by Petugas Hotel', [
                'room_type_id' => $id,
                'action' => $validated['action'],
                'adjustment' => $adjustment,
                'new_available' => $roomType->fresh()->available_rooms
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'available_rooms' => $roomType->fresh()->available_rooms
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengatur ketersediaan kamar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get hotel photos
     */
    public function getHotelPhotos(Request $request)
    {
        try {
            $query = HotelPhoto::with('roomType');

            if ($request->has('room_type_id') && $request->room_type_id != '') {
                $query->where('room_type_id', $request->room_type_id);
            }

            $photos = $query->orderBy('sort_order')->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'photos' => $photos->map(function ($photo) {
                    return [
                        'id' => $photo->id,
                        'title' => $photo->title,
                        'description' => $photo->description,
                        'image_url' => $photo->image_url,
                        'image_path' => $photo->image_path,
                        'category' => $photo->category,
                        'room_type' => $photo->roomType ? $photo->roomType->name : 'Umum',
                        'room_type_id' => $photo->room_type_id,
                        'sort_order' => $photo->sort_order,
                        'is_featured' => $photo->is_featured,
                        'is_active' => $photo->is_active,
                        'created_at' => $photo->created_at ? $photo->created_at->format('d M Y H:i') : null,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get hotel photos for Petugas Hotel', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil foto hotel.'
            ], 500);
        }
    }

    /**
     * Store hotel photo
     */
    public function storeHotelPhoto(Request $request)
    {
        try {
            // Log incoming request
            Log::info('Upload photo request received', [
                'has_file' => $request->hasFile('image'),
                'all_files' => $request->allFiles(),
                'all_data' => $request->except('image')
            ]);

            // Clean up empty values
            if ($request->sort_order === '' || $request->sort_order === null) {
                $request->merge(['sort_order' => 0]);
            }

            $validated = $request->validate([
                'room_type_id' => 'required|exists:room_types,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
                'sort_order' => 'nullable|integer|min:0',
                'is_featured' => 'nullable|in:0,1,true,false,on',
                'is_active' => 'nullable|in:0,1,true,false,on'
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('hotel_photos', $filename, 'public');

                $photo = HotelPhoto::create([
                    'room_type_id' => $request->room_type_id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'image_path' => $path,
                    'category' => 'room',
                    'sort_order' => $request->sort_order ?? 0,
                    'is_featured' => $request->boolean('is_featured', false),
                    'is_active' => $request->boolean('is_active', true)
                ]);

                Log::info('Hotel photo uploaded by Petugas Hotel', [
                    'photo_id' => $photo->id,
                    'room_type_id' => $photo->room_type_id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Foto hotel berhasil diupload!',
                    'photo' => $photo
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'File foto tidak ditemukan.'
            ], 400);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to upload hotel photo by Petugas Hotel', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update hotel photo
     */
    public function updateHotelPhoto(Request $request, $id)
    {
        try {
            $photo = HotelPhoto::findOrFail($id);

            // Clean up empty values
            if ($request->sort_order === '' || $request->sort_order === null) {
                $request->merge(['sort_order' => 0]);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
                'room_type_id' => 'nullable|exists:room_types,id',
                'sort_order' => 'nullable|integer|min:0',
                'is_featured' => 'nullable|in:0,1,true,false,on',
                'is_active' => 'nullable|in:0,1,true,false,on'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = [
                'title' => $request->title,
                'description' => null,
                'category' => $request->room_type_id ? 'room' : 'general',
                'room_type_id' => $request->room_type_id,
                'sort_order' => $request->sort_order ?? $photo->sort_order,
                'is_featured' => $request->boolean('is_featured', $photo->is_featured),
                'is_active' => $request->boolean('is_active', $photo->is_active)
            ];

            // Handle new image upload if provided
            if ($request->hasFile('image')) {
                // Delete old image
                if ($photo->image_path && Storage::disk('public')->exists($photo->image_path)) {
                    Storage::disk('public')->delete($photo->image_path);
                }

                // Upload new image
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('hotel_photos', $filename, 'public');
                $updateData['image_path'] = $path;
            }

            $photo->update($updateData);

            Log::info('Hotel photo updated by Petugas Hotel', [
                'photo_id' => $photo->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto hotel berhasil diupdate!',
                'photo' => [
                    'id' => $photo->id,
                    'title' => $photo->title,
                    'description' => $photo->description,
                    'image_url' => $photo->image_url,
                    'category' => $photo->category,
                    'room_type_id' => $photo->room_type_id,
                    'is_featured' => $photo->is_featured,
                    'is_active' => $photo->is_active
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update hotel photo by Petugas Hotel', [
                'photo_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete hotel photo
     */
    public function deleteHotelPhoto($id)
    {
        try {
            $photo = HotelPhoto::findOrFail($id);
            
            if ($photo->image_path && Storage::disk('public')->exists($photo->image_path)) {
                Storage::disk('public')->delete($photo->image_path);
            }

            $photo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Foto hotel berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle photo featured status
     */
    public function togglePhotoFeatured($id)
    {
        try {
            $photo = HotelPhoto::findOrFail($id);
            
            if (!$photo->is_featured) {
                HotelPhoto::where('room_type_id', $photo->room_type_id)
                    ->update(['is_featured' => false]);
            }
            
            $photo->is_featured = !$photo->is_featured;
            $photo->save();

            return response()->json([
                'success' => true,
                'message' => 'Status unggulan foto berhasil diubah.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle photo active status
     */
    public function togglePhotoStatus($id)
    {
        try {
            $photo = HotelPhoto::findOrFail($id);
            $photo->is_active = !$photo->is_active;
            $photo->save();

            return response()->json([
                'success' => true,
                'message' => 'Status foto berhasil diubah.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }
}

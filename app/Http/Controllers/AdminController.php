<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Package;
use App\Models\RoomBooking;
use App\Models\RoomType;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function showRegisterForm()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return back()->withErrors($validator)->withInput($request->except('password', 'password_confirmation'));
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role' => 'admin', // Set role as admin
            ]);

            Log::info('New admin registered', [
                'admin_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'registered_at' => now()
            ]);

            // Auto login after registration
            Auth::login($user);
            $request->session()->regenerate();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registrasi admin berhasil!',
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                    ],
                    'redirect_url' => '/admin/dashboard'
                ]);
            }

            return redirect('/admin/dashboard')->with('success', 'Registrasi admin berhasil! Selamat datang, ' . $user->name . '!');

        } catch (\Exception $e) {
            Log::error('Admin registration failed', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat registrasi admin.'
                ], 500);
            }

            return back()->withErrors(['error' => 'Terjadi kesalahan saat registrasi admin.'])->withInput($request->except('password', 'password_confirmation'));
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user is admin
            if ($user->role !== 'admin') {
                Auth::logout();
                
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Akses ditolak. Anda bukan administrator.',
                        'errors' => [
                            'email' => ['Akses ditolak. Anda bukan administrator.']
                        ]
                    ], 403);
                }
                
                return back()->withErrors([
                    'email' => 'Akses ditolak. Anda bukan administrator.',
                ])->withInput($request->except('password'));
            }
            
            $request->session()->regenerate();
            
            Log::info('Admin logged in', [
                'email' => $user->email,
                'role' => $user->role,
                'user_id' => $user->id
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login admin berhasil!',
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                    ],
                    'redirect_url' => '/admin/dashboard'
                ]);
            }
            
            return redirect('/admin/dashboard')->with('success', 'Selamat datang, Admin!');
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
                'errors' => [
                    'email' => ['Email atau password salah.']
                ]
            ], 422);
        }
        
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->except('password'));
    }

    public function dashboard()
    {
        // Get statistics for dashboard (clean design metrics)
        $totalUsers = User::where('role', 'user')->count();
        $totalRoomTypes = RoomType::count();
        $totalTickets = Package::count();
        
        // Get available rooms count
        $totalAvailableRooms = RoomType::sum('available_rooms');
        $totalRooms = RoomType::sum('total_rooms');
        $occupiedRooms = $totalRooms - $totalAvailableRooms;
        
        // Get detailed room availability by type
        $roomTypesAvailability = RoomType::select('id', 'name', 'total_rooms', 'available_rooms', 'price_per_night', 'is_active')
            ->orderBy('name')
            ->get()
            ->map(function($roomType) {
                return [
                    'id' => $roomType->id,
                    'name' => $roomType->name,
                    'total_rooms' => $roomType->total_rooms,
                    'available_rooms' => $roomType->available_rooms,
                    'occupied_rooms' => $roomType->total_rooms - $roomType->available_rooms,
                    'occupancy_rate' => $roomType->total_rooms > 0 ? round((($roomType->total_rooms - $roomType->available_rooms) / $roomType->total_rooms) * 100, 1) : 0,
                    'price_per_night' => $roomType->price_per_night,
                    'is_active' => $roomType->is_active,
                    'status' => $roomType->available_rooms == 0 ? 'full' : ($roomType->available_rooms <= 2 ? 'low' : 'available')
                ];
            });
        
        // Get recent bookings (including room bookings)
        $recentBookings = Booking::with(['user', 'roomBookings.roomType'])
            ->latest()
            ->limit(5)
            ->get();
            
        // Get room booking statistics
        $totalRoomBookings = RoomBooking::count();
        $todayRoomBookings = RoomBooking::whereDate('check_in_date', today())->count();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalRoomTypes',
            'totalTickets',
            'recentBookings',
            'totalRoomBookings',
            'todayRoomBookings',
            'totalAvailableRooms',
            'totalRooms',
            'occupiedRooms',
            'roomTypesAvailability'
        ))->with('totalHotels', $totalRoomTypes);
    }

    public function users()
    {
        $users = User::where('role', 'user')->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'visitors', 'bookingDetails.package', 'roomBookings.roomType']);
        
        // Search by booking code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'LIKE', "%{$search}%")
                  ->orWhere('booker_name', 'LIKE', "%{$search}%")
                  ->orWhere('booker_email', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Filter by check-in status
        if ($request->filled('check_in_status')) {
            $query->where('check_in_status', $request->check_in_status);
        }
        
        // Filter by booking type (room or package)
        if ($request->filled('booking_type')) {
            if ($request->booking_type === 'room') {
                $query->whereHas('roomBookings');
            } elseif ($request->booking_type === 'package') {
                $query->whereHas('bookingDetails');
            }
        }
        
        $bookings = $query->latest()->paginate(20)->withQueryString();
        
        return view('admin.bookings', compact('bookings'));
    }

    public function hotelBookings(Request $request)
    {
        $query = Booking::with(['user', 'roomBookings.roomType'])
            ->whereHas('roomBookings'); // Only bookings with room bookings
        
        // Search by booking code, name, or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'LIKE', "%{$search}%")
                  ->orWhere('booker_name', 'LIKE', "%{$search}%")
                  ->orWhere('booker_email', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Filter by check-in status
        if ($request->filled('check_in_status')) {
            $query->where('check_in_status', $request->check_in_status);
        }

        // Filter by room type
        if ($request->filled('room_type')) {
            $query->whereHas('roomBookings.roomType', function($q) use ($request) {
                $q->where('id', $request->room_type);
            });
        }
        
        $bookings = $query->latest()->paginate(20)->withQueryString();
        
        // Get room types for filter dropdown
        $roomTypes = RoomType::active()->get();
        
        return view('admin.hotel-bookings', compact('bookings', 'roomTypes'));
    }

    public function ticketBookings(Request $request)
    {
        $query = Booking::with(['user', 'visitors', 'bookingDetails.package'])
            ->whereHas('bookingDetails'); // Only bookings with ticket/package bookings
        
        // Search by booking code, name, or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'LIKE', "%{$search}%")
                  ->orWhere('booker_name', 'LIKE', "%{$search}%")
                  ->orWhere('booker_email', 'LIKE', "%{$search}%");
            });
        }
        
        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Filter by check-in status
        if ($request->filled('check_in_status')) {
            $query->where('check_in_status', $request->check_in_status);
        }

        // Filter by package
        if ($request->filled('package')) {
            $query->whereHas('bookingDetails.package', function($q) use ($request) {
                $q->where('id', $request->package);
            });
        }
        
        $bookings = $query->latest()->paginate(20)->withQueryString();
        
        // Get packages for filter dropdown
        $packages = Package::active()->get();
        
        return view('admin.ticket-bookings', compact('bookings', 'packages'));
    }

    public function bookingDetail($id)
    {
        try {
            $booking = Booking::with(['user', 'visitors', 'bookingDetails.package', 'roomBookings.roomType'])->findOrFail($id);
            
            $html = view('admin.partials.booking-detail', compact('booking'))->render();
            
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan.'
            ], 404);
        }
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,expired',
            'check_in_status' => 'required|in:pending,checked_in'
        ]);

        $booking = Booking::with('roomBookings')->findOrFail($id);
        
        $oldCheckInStatus = $booking->check_in_status;
        
        $booking->update([
            'payment_status' => $request->payment_status,
            'check_in_status' => $request->check_in_status
        ]);

        // Generate QR code if payment is paid and QR doesn't exist
        if ($request->payment_status === 'paid' && !$booking->qr_code) {
            $booking->update(['qr_code' => $booking->generateQRCode()]);
        }

        // Handle room booking status changes
        if ($booking->roomBookings->count() > 0) {
            foreach ($booking->roomBookings as $roomBooking) {
                // If check-in status changed to checked_in, update room booking
                if ($oldCheckInStatus !== 'checked_in' && $request->check_in_status === 'checked_in') {
                    $roomBooking->checkIn();
                }
                
                // If check-in status changed from checked_in to pending (checkout), release rooms
                if ($oldCheckInStatus === 'checked_in' && $request->check_in_status === 'pending') {
                    $roomBooking->checkOut();
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Status booking berhasil diupdate.'
        ]);
    }

    public function deleteBooking($id)
    {
        try {
            $booking = Booking::with(['bookingDetails', 'visitors', 'roomBookings.roomType'])->findOrFail($id);
            
            // Log the deletion attempt
            Log::info('Booking deletion attempt', [
                'booking_id' => $id,
                'booking_code' => $booking->booking_code,
                'booker_name' => $booking->booker_name,
                'deleted_by' => Auth::user()->email
            ]);

            DB::beginTransaction();

            // Release rooms if this is a hotel booking
            if ($booking->roomBookings->count() > 0) {
                foreach ($booking->roomBookings as $roomBooking) {
                    $roomType = $roomBooking->roomType;
                    $numberOfRooms = $roomBooking->number_of_rooms;
                    
                    // Release the rooms back to available inventory
                    $roomType->increment('available_rooms', $numberOfRooms);
                    
                    Log::info('Rooms released after booking deletion', [
                        'booking_id' => $id,
                        'room_type_id' => $roomType->id,
                        'room_type_name' => $roomType->name,
                        'rooms_released' => $numberOfRooms,
                        'new_available_rooms' => $roomType->fresh()->available_rooms,
                        'total_rooms' => $roomType->total_rooms
                    ]);
                }
                
                // Delete room bookings
                $booking->roomBookings()->delete();
            }

            // Delete related data (cascade delete)
            $booking->bookingDetails()->delete();
            $booking->visitors()->delete();
            
            // Delete the booking
            $booking->delete();

            DB::commit();

            Log::info('Booking deleted successfully', [
                'booking_id' => $id,
                'booking_code' => $booking->booking_code
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dihapus dan kamar telah dikembalikan ke inventory!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Failed to delete booking', [
                'booking_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus booking.'
            ], 500);
        }
    }

    public function packages()
    {
        $packages = Package::latest()->paginate(20);
        return view('admin.packages', compact('packages'));
    }

    // Clean design renamed sections
    public function hotels()
    {
        return view('admin.hotels');
    }

    public function storeRoomType(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price_per_night' => 'required|numeric|min:0',
                'max_occupancy' => 'required|integer|min:1',
                'total_rooms' => 'required|integer|min:1'
            ]);

            $validated['is_active'] = $request->has('is_active') ? true : false;
            $validated['available_rooms'] = $validated['total_rooms']; // Initialize available rooms

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

    public function updateRoomType(Request $request, $id)
    {
        try {
            $roomType = RoomType::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price_per_night' => 'required|numeric|min:0',
                'max_occupancy' => 'required|integer|min:1',
                'total_rooms' => 'required|integer|min:1'
            ]);

            $validated['is_active'] = $request->has('is_active') ? true : false;
            
            // Update available rooms if total rooms changed
            if ($validated['total_rooms'] != $roomType->total_rooms) {
                $difference = $validated['total_rooms'] - $roomType->total_rooms;
                $validated['available_rooms'] = $roomType->available_rooms + $difference;
                
                // Ensure available rooms doesn't go negative
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

    public function deleteRoomType($id)
    {
        try {
            $roomType = RoomType::findOrFail($id);
            
            // Check if there are active bookings for this room type
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
                
                // Ensure we don't exceed total rooms
                if ($newAvailable > $roomType->total_rooms) {
                    return response()->json([
                        'success' => false,
                        'message' => "Tidak dapat menambah kamar. Maksimal kamar tersedia adalah {$roomType->total_rooms}."
                    ], 400);
                }
                
                $roomType->increment('available_rooms', $adjustment);
                $message = "Berhasil menambah {$adjustment} kamar tersedia.";
                
            } else { // subtract
                if ($adjustment > $currentAvailable) {
                    return response()->json([
                        'success' => false,
                        'message' => "Tidak dapat mengurangi {$adjustment} kamar. Hanya tersedia {$currentAvailable} kamar."
                    ], 400);
                }
                
                $roomType->decrement('available_rooms', $adjustment);
                $message = "Berhasil mengurangi {$adjustment} kamar tersedia.";
            }

            // Log the adjustment
            Log::info('Room availability adjusted', [
                'room_type_id' => $id,
                'room_type_name' => $roomType->name,
                'action' => $validated['action'],
                'adjustment' => $adjustment,
                'previous_available' => $currentAvailable,
                'new_available' => $roomType->fresh()->available_rooms,
                'adjusted_by' => Auth::user()->email
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'available_rooms' => $roomType->fresh()->available_rooms,
                    'total_rooms' => $roomType->total_rooms
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to adjust available rooms', [
                'room_type_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengatur ketersediaan kamar: ' . $e->getMessage()
            ], 500);
        }
    }

    public function tickets()
    {
        // Reuse packages (tickets) data
        $packages = Package::latest()->paginate(20);
        return view('admin.tickets', compact('packages'));
    }

    public function restaurants()
    {
        // Placeholder view for Restaurants CRUD (3 restaurants)
        return view('admin.restaurants');
    }

    public function editPackage($id)
    {
        $package = Package::findOrFail($id);
        return view('admin.edit-package', compact('package'));
    }

    public function updatePackage(Request $request, $id)
    {
        // Debug logging
        Log::info('Update package request received', [
            'package_id' => $id,
            'request_data' => $request->all(),
            'user' => Auth::user()->email ?? 'unknown'
        ]);

        $package = Package::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|string',
            'badge' => 'nullable|string|max:50',
            'is_active' => 'nullable|in:on,1,true'
        ]);

        if ($validator->fails()) {
            Log::warning('Package update validation failed', [
                'package_id' => $id,
                'errors' => $validator->errors()->toArray()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $oldPrice = $package->price;
        $newPrice = $request->price;

        try {
            DB::beginTransaction();

            // Update package
            $updateData = [
                'name' => $request->name,
                'description' => $request->description ?: '',
                'price' => $newPrice,
                'features' => $request->features ? array_map('trim', explode(',', $request->features)) : null,
                'badge' => $request->badge ?: '',
                'is_active' => $request->has('is_active') && in_array($request->is_active, ['on', '1', 'true', true])
            ];

            Log::info('Updating package with data', [
                'package_id' => $id,
                'update_data' => $updateData
            ]);

            $package->update($updateData);

            // Refresh package to get updated data
            $package->refresh();

            Log::info('Package updated successfully', [
                'package_id' => $id,
                'old_price' => $oldPrice,
                'new_price' => $package->price,
                'updated_at' => $package->updated_at
            ]);

            // Log price change and sync with Midtrans
            if ($oldPrice != $newPrice) {
                $this->midtransService->logPriceSync($package, $oldPrice, $newPrice, Auth::id());
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Package berhasil diperbarui!',
                    'package' => $package->fresh()
                ]);
            }

            return redirect()->route('admin.packages')->with('success', 'Package berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Failed to update package', [
                'package_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user' => Auth::user()->email ?? 'unknown'
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui package: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui package: ' . $e->getMessage()])->withInput();
        }
    }

    public function createPackage()
    {
        return view('admin.create-package');
    }

    public function storePackage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'features' => 'nullable|string',
            'badge' => 'nullable|string|max:50',
            'is_active' => 'nullable|in:on,1,true'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            $package = Package::create([
                'name' => $request->name,
                'description' => $request->description ?: '',
                'price' => $request->price,
                'features' => $request->features ? array_map('trim', explode(',', $request->features)) : null,
                'badge' => $request->badge ?: '',
                'is_active' => $request->has('is_active') && in_array($request->is_active, ['on', '1', 'true', true])
            ]);

            Log::info('New package created', [
                'package_id' => $package->id,
                'package_name' => $package->name,
                'price' => $package->price,
                'created_by' => Auth::user()->email
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Package berhasil dibuat!',
                    'package' => $package
                ]);
            }

            return redirect()->route('admin.packages')->with('success', 'Package berhasil dibuat!');

        } catch (\Exception $e) {
            Log::error('Failed to create package', [
                'error' => $e->getMessage(),
                'user' => Auth::user()->email
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat membuat package.'
                ], 500);
            }

            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat package.'])->withInput();
        }
    }

    public function deletePackage($id)
    {
        try {
            $package = Package::findOrFail($id);
            
            // Check if package has bookings
            $hasBookings = $package->bookingDetails()->exists();
            $bookingCount = $package->bookingDetails()->count();
            
            if ($hasBookings) {
                return response()->json([
                    'success' => false,
                    'message' => "Ticket tidak dapat dihapus karena sudah memiliki {$bookingCount} booking. Hapus booking terkait terlebih dahulu atau nonaktifkan ticket ini."
                ], 400);
            }

            $packageName = $package->name;
            $package->delete();

            Log::info('Package deleted', [
                'package_name' => $packageName,
                'deleted_by' => Auth::user()->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Package berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete package', [
                'package_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus package.'
            ], 500);
        }
    }
}

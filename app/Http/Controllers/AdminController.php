<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Package;
use App\Models\RoomBooking;
use App\Models\RoomType;
use App\Models\HotelPhoto;
use App\Models\Gallery;
use App\Models\Restaurant;
use App\Models\MenuItem;
use App\Models\TopAttraction;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'role' => 'required|in:admin,petugas_loket,petugas_hotel',
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
                'role' => $request->role, // Set role from form
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

            // Determine redirect URL based on role
            $redirectUrl = '/';
            $welcomeMessage = 'Registrasi berhasil! Selamat datang, ' . $user->name . '!';
            
            if ($user->role === User::ROLE_ADMIN) {
                $redirectUrl = '/admin/dashboard';
                $welcomeMessage = 'Registrasi admin berhasil! Selamat datang, ' . $user->name . '!';
            } elseif ($user->role === User::ROLE_PETUGAS_LOKET) {
                $redirectUrl = '/petugas-loket/dashboard';
                $welcomeMessage = 'Registrasi petugas loket berhasil! Selamat datang, ' . $user->name . '!';
            } elseif ($user->role === User::ROLE_PETUGAS_HOTEL) {
                $redirectUrl = '/petugas-hotel/dashboard';
                $welcomeMessage = 'Registrasi petugas hotel berhasil! Selamat datang, ' . $user->name . '!';
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $welcomeMessage,
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                    ],
                    'redirect_url' => $redirectUrl
                ]);
            }

            return redirect($redirectUrl)->with('success', $welcomeMessage);

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
            
            // Check if user is staff (admin, petugas_loket, or petugas_hotel)
            if (!in_array($user->role, ['admin', 'petugas_loket', 'petugas_hotel'])) {
                Auth::logout();
                
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Akses ditolak. Anda bukan staff.',
                        'errors' => [
                            'email' => ['Akses ditolak. Hanya staff yang dapat login di sini.']
                        ]
                    ], 403);
                }
                
                return back()->withErrors([
                    'email' => 'Akses ditolak. Hanya staff yang dapat login di sini.',
                ])->withInput($request->except('password'));
            }
            
            $request->session()->regenerate();
            
            // Determine redirect URL and message based on role
            $redirectUrl = '/admin/dashboard';
            $welcomeMessage = 'Selamat datang!';
            
            if ($user->role === User::ROLE_ADMIN) {
                $redirectUrl = '/admin/dashboard';
                $welcomeMessage = 'Selamat datang, Admin!';
            } elseif ($user->role === User::ROLE_PETUGAS_LOKET) {
                $redirectUrl = '/petugas-loket/dashboard';
                $welcomeMessage = 'Selamat datang, Petugas Loket!';
            } elseif ($user->role === User::ROLE_PETUGAS_HOTEL) {
                $redirectUrl = '/petugas-hotel/dashboard';
                $welcomeMessage = 'Selamat datang, Petugas Hotel!';
            }
            
            Log::info('Staff logged in', [
                'email' => $user->email,
                'role' => $user->role,
                'user_id' => $user->id,
                'redirect_to' => $redirectUrl
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $welcomeMessage,
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                    ],
                    'redirect_url' => $redirectUrl
                ]);
            }
            
            return redirect($redirectUrl)->with('success', $welcomeMessage);
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
        
        // Get Top Attractions statistics
        $totalAttractions = TopAttraction::count();
        $activeAttractions = TopAttraction::where('is_active', true)->count();
        
        // Get Gallery statistics
        $totalGalleryPhotos = Gallery::count();
        $activeGalleryPhotos = Gallery::where('is_active', true)->count();
        
        // Get booking revenue statistics
        $totalRevenue = Booking::where('payment_status', 'paid')->sum('total_amount');
        $paidBookings = Booking::where('payment_status', 'paid')->count();
        $pendingBookings = Booking::where('payment_status', 'pending')->count();
        $totalBookings = Booking::count();
        
        // Calculate occupancy rate
        $occupancyRate = $totalRooms > 0 ? round((($totalRooms - $totalAvailableRooms) / $totalRooms) * 100) : 0;
        
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
            'roomTypesAvailability',
            'totalAttractions',
            'activeAttractions',
            'totalGalleryPhotos',
            'activeGalleryPhotos',
            'totalRevenue',
            'paidBookings',
            'pendingBookings',
            'totalBookings',
            'occupancyRate'
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
                'max_adults' => 'required|integer|min:1',
                'max_children' => 'required|integer|min:0',
                'max_occupancy' => 'required|integer|min:1',
                'total_rooms' => 'required|integer|min:1',
                'amenities' => 'nullable|array',
                'amenities.*' => 'string|max:255'
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
                'max_adults' => 'required|integer|min:1',
                'max_children' => 'required|integer|min:0',
                'max_occupancy' => 'required|integer|min:1',
                'total_rooms' => 'required|integer|min:1',
                'amenities' => 'nullable|array',
                'amenities.*' => 'string|max:255'
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
        $restaurants = Restaurant::with('menuItems')->orderBy('name')->get();
        return view('admin.restaurants', compact('restaurants'));
     }

    public function topAttractions()
    {
        $attractions = TopAttraction::orderBy('sort_order')->orderBy('created_at', 'desc')->get();
        return view('admin.top-attractions', compact('attractions'));
    }

    public function getTopAttraction($id)
    {
        try {
            $attraction = TopAttraction::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $attraction,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Wahana tidak ditemukan.',
            ], 404);
        }
    }

    public function storeTopAttraction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $sortOrder = TopAttraction::max('sort_order');
            if ($sortOrder === null) {
                $sortOrder = 0;
            } else {
                $sortOrder++;
            }

            $data = [
                'title' => $request->title,
                'location' => $request->location,
                'description' => $request->description,
                'is_active' => true,
                'sort_order' => $sortOrder,
            ];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('top_attractions', $filename, 'public');
                $data['image_path'] = $path;
            }

            $attraction = TopAttraction::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Wahana berhasil ditambahkan.',
                'data' => $attraction,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to store top attraction', [
                'error' => $e->getMessage(),
                'user' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan wahana.',
            ], 500);
        }
    }

    public function updateTopAttraction(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $attraction = TopAttraction::findOrFail($id);

            DB::beginTransaction();

            $data = [
                'title' => $request->title,
                'location' => $request->location,
                'description' => $request->description,
            ];

            if ($request->hasFile('image')) {
                if ($attraction->image_path && Storage::disk('public')->exists($attraction->image_path)) {
                    Storage::disk('public')->delete($attraction->image_path);
                }

                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('top_attractions', $filename, 'public');
                $data['image_path'] = $path;
            }

            $attraction->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Wahana berhasil diperbarui.',
                'data' => $attraction->fresh(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update top attraction', [
                'attraction_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui wahana.',
            ], 500);
        }
    }

    public function deleteTopAttraction($id)
    {
        try {
            $attraction = TopAttraction::findOrFail($id);

            DB::beginTransaction();

            if ($attraction->image_path && Storage::disk('public')->exists($attraction->image_path)) {
                Storage::disk('public')->delete($attraction->image_path);
            }

            $title = $attraction->title;
            $attraction->delete();

            DB::commit();

            Log::info('Top attraction deleted', [
                'title' => $title,
                'deleted_by' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Wahana berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete top attraction', [
                'attraction_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus wahana.',
            ], 500);
        }
    }

    public function toggleTopAttractionStatus($id)
    {
        try {
            $attraction = TopAttraction::findOrFail($id);
            $attraction->is_active = !$attraction->is_active;
            $attraction->save();

            return response()->json([
                'success' => true,
                'message' => 'Status wahana berhasil diubah.',
                'is_active' => $attraction->fresh()->is_active,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to toggle top attraction status', [
                'attraction_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status wahana.',
            ], 500);
        }
    }

    public function getRestaurant($id)
    {
        try {
            $restaurant = Restaurant::with('menuItems')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $restaurant,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Restoran tidak ditemukan.',
            ], 404);
        }
    }

    public function storeRestaurant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cuisine_type' => 'nullable|string|max:255',
            'operating_hours' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'features' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $slugBase = Str::slug($request->name);
            $slug = $slugBase;
            $counter = 1;

            while (Restaurant::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $counter;
                $counter++;
            }

            $features = null;
            if ($request->filled('features')) {
                $featuresArray = array_filter(array_map('trim', explode(',', $request->features)));
                if (!empty($featuresArray)) {
                    $features = $featuresArray;
                }
            }

            $data = [
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'cuisine_type' => $request->cuisine_type,
                'features' => $features,
                'operating_hours' => $request->operating_hours,
                'location' => $request->location,
                'is_active' => true,
            ];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('restaurants', $filename, 'public');
                $data['image_path'] = $path;
            }

            $restaurant = Restaurant::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Restoran berhasil ditambahkan.',
                'data' => $restaurant,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to store restaurant', [
                'error' => $e->getMessage(),
                'user' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan restoran.',
            ], 500);
        }
    }

    public function updateRestaurant(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cuisine_type' => 'nullable|string|max:255',
            'operating_hours' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'features' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $restaurant = Restaurant::findOrFail($id);

            DB::beginTransaction();

            $features = null;
            if ($request->filled('features')) {
                $featuresArray = array_filter(array_map('trim', explode(',', $request->features)));
                if (!empty($featuresArray)) {
                    $features = $featuresArray;
                }
            }

            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'cuisine_type' => $request->cuisine_type,
                'features' => $features,
                'operating_hours' => $request->operating_hours,
                'location' => $request->location,
            ];

            if ($request->hasFile('image')) {
                if ($restaurant->image_path && Storage::disk('public')->exists($restaurant->image_path)) {
                    Storage::disk('public')->delete($restaurant->image_path);
                }

                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('restaurants', $filename, 'public');
                $data['image_path'] = $path;
            }

            $restaurant->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Restoran berhasil diperbarui.',
                'data' => $restaurant->fresh(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update restaurant', [
                'restaurant_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui restoran.',
            ], 500);
        }
    }

    public function deleteRestaurant($id)
    {
        try {
            $restaurant = Restaurant::with('menuItems')->findOrFail($id);

            DB::beginTransaction();

            if ($restaurant->image_path && Storage::disk('public')->exists($restaurant->image_path)) {
                Storage::disk('public')->delete($restaurant->image_path);
            }

            foreach ($restaurant->menuItems as $item) {
                if ($item->image_path && Storage::disk('public')->exists($item->image_path)) {
                    Storage::disk('public')->delete($item->image_path);
                }
            }

            $restaurantName = $restaurant->name;
            $restaurant->delete();

            DB::commit();

            Log::info('Restaurant deleted', [
                'restaurant_name' => $restaurantName,
                'deleted_by' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Restoran berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete restaurant', [
                'restaurant_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus restoran.',
            ], 500);
        }
    }

    public function getMenuItem($id)
    {
        try {
            $menuItem = MenuItem::with('restaurant')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $menuItem,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Menu tidak ditemukan.',
            ], 404);
        }
    }

    public function storeMenuItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:makanan,minuman',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $sortOrder = MenuItem::where('restaurant_id', $request->restaurant_id)->max('sort_order');
            if ($sortOrder === null) {
                $sortOrder = 0;
            } else {
                $sortOrder++;
            }

            $data = [
                'restaurant_id' => $request->restaurant_id,
                'name' => $request->name,
                'description' => $request->description,
                'category' => $request->category,
                'price' => $request->price,
                'is_active' => true,
                'sort_order' => $sortOrder,
            ];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('menu_items', $filename, 'public');
                $data['image_path'] = $path;
            }

            $menuItem = MenuItem::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil ditambahkan.',
                'data' => $menuItem,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to store menu item', [
                'error' => $e->getMessage(),
                'user' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan menu.',
            ], 500);
        }
    }

    public function updateMenuItem(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:makanan,minuman',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $menuItem = MenuItem::findOrFail($id);

            DB::beginTransaction();

            $data = [
                'restaurant_id' => $request->restaurant_id,
                'name' => $request->name,
                'description' => $request->description,
                'category' => $request->category,
                'price' => $request->price,
            ];

            if ($request->hasFile('image')) {
                if ($menuItem->image_path && Storage::disk('public')->exists($menuItem->image_path)) {
                    Storage::disk('public')->delete($menuItem->image_path);
                }

                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('menu_items', $filename, 'public');
                $data['image_path'] = $path;
            }

            $menuItem->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil diperbarui.',
                'data' => $menuItem->fresh(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update menu item', [
                'menu_item_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui menu.',
            ], 500);
        }
    }

    public function deleteMenuItem($id)
    {
        try {
            $menuItem = MenuItem::findOrFail($id);

            DB::beginTransaction();

            if ($menuItem->image_path && Storage::disk('public')->exists($menuItem->image_path)) {
                Storage::disk('public')->delete($menuItem->image_path);
            }

            $menuName = $menuItem->name;
            $menuItem->delete();

            DB::commit();

            Log::info('Menu item deleted', [
                'menu_name' => $menuName,
                'deleted_by' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete menu item', [
                'menu_item_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus menu.',
            ], 500);
        }
    }

    public function toggleMenuItemStatus($id)
    {
        try {
            $menuItem = MenuItem::findOrFail($id);
            $currentStatus = (bool) $menuItem->is_active;
            $newStatus = !$currentStatus;
            $menuItem->update(['is_active' => $newStatus ? 1 : 0]);

            return response()->json([
                'success' => true,
                'message' => 'Status menu berhasil diubah.',
                'is_active' => (bool) $menuItem->fresh()->is_active,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to toggle menu item status', [
                'menu_item_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status menu.',
            ], 500);
        }
    }

    /**
     * Show edit package form
     * @param int $id Package ID
     * @return \Illuminate\View\View
     */
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

    /**
     * Toggle package active/inactive status
     */
    public function togglePackageStatus($id)
    {
        try {
            $package = Package::findOrFail($id);
            $package->is_active = !$package->is_active;
            $package->save();

            Log::info('Package status toggled', [
                'package_id' => $package->id,
                'package_name' => $package->name,
                'new_status' => $package->is_active ? 'active' : 'inactive',
                'updated_by' => Auth::user()->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status package berhasil diubah!',
                'is_active' => $package->is_active
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to toggle package status', [
                'package_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status package.'
            ], 500);
        }
    }

    // ============ TOP GALLERY CRUD METHODS ============

    public function topGallery()
    {
        $galleries = Gallery::orderBy('sort_order')
            ->orderByDesc('photo_date')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.top-gallery', compact('galleries'));
    }

    public function getGallery($id)
    {
        try {
            $gallery = Gallery::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $gallery->id,
                    'title' => $gallery->title,
                    'image_url' => $gallery->image_url,
                    'photo_date' => $gallery->photo_date ? $gallery->photo_date->format('Y-m-d') : null,
                    'sort_order' => $gallery->sort_order,
                    'is_active' => (bool) $gallery->is_active,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Foto galeri tidak ditemukan.',
            ], 404);
        }
    }

    public function storeGallery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'photo_date' => 'required|date',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|in:0,1,true,false,on',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $storagePath = storage_path('app/public/galleries');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            $path = $image->storeAs('galleries', $filename, 'public');

            $galleryData = [
                'title' => $request->title,
                'image_path' => $path,
                'photo_date' => $request->photo_date,
                'sort_order' => $request->sort_order ?? 0,
                'is_active' => $request->boolean('is_active', true),
            ];

            $gallery = Gallery::create($galleryData);

            DB::commit();

            Log::info('Gallery photo created', [
                'gallery_id' => $gallery->id,
                'title' => $gallery->title,
                'uploaded_by' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto galeri berhasil ditambahkan.',
                'data' => $gallery,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to store gallery photo', [
                'error' => $e->getMessage(),
                'user' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan foto galeri.',
            ], 500);
        }
    }

    public function updateGallery(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'photo_date' => 'required|date',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|in:0,1,true,false,on',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $gallery = Gallery::findOrFail($id);

            DB::beginTransaction();

            $updateData = [
                'title' => $request->title,
                'photo_date' => $request->photo_date,
                'sort_order' => $request->sort_order ?? $gallery->sort_order,
                'is_active' => $request->boolean('is_active', $gallery->is_active),
            ];

            if ($request->hasFile('image')) {
                if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                    Storage::disk('public')->delete($gallery->image_path);
                }

                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('galleries', $filename, 'public');
                $updateData['image_path'] = $path;
            }

            $gallery->update($updateData);

            DB::commit();

            Log::info('Gallery photo updated', [
                'gallery_id' => $gallery->id,
                'updated_by' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto galeri berhasil diperbarui.',
                'data' => $gallery->fresh(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update gallery photo', [
                'gallery_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui foto galeri.',
            ], 500);
        }
    }

    public function deleteGallery($id)
    {
        try {
            $gallery = Gallery::findOrFail($id);

            DB::beginTransaction();

            if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }

            $title = $gallery->title;
            $gallery->delete();

            DB::commit();

            Log::info('Gallery photo deleted', [
                'gallery_id' => $id,
                'title' => $title,
                'deleted_by' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto galeri berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete gallery photo', [
                'gallery_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus foto galeri.',
            ], 500);
        }
    }

    public function toggleGalleryStatus($id)
    {
        try {
            $gallery = Gallery::findOrFail($id);
            $gallery->is_active = !$gallery->is_active;
            $gallery->save();

            Log::info('Gallery photo status toggled', [
                'gallery_id' => $gallery->id,
                'new_status' => $gallery->is_active ? 'active' : 'inactive',
                'updated_by' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status foto galeri berhasil diubah.',
                'is_active' => $gallery->is_active,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to toggle gallery status', [
                'gallery_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email ?? 'unknown',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status foto galeri.',
            ], 500);
        }
    }

    // ============ HOTEL PHOTOS CRUD METHODS ============

    /**
     * Get hotel photos for a specific room type or all photos
     */
    public function getHotelPhotos(Request $request)
    {
        try {
            $query = HotelPhoto::with('roomType');

            // Filter by room type if specified
            if ($request->filled('room_type_id')) {
                $query->where('room_type_id', $request->room_type_id);
            }

            // Filter by category if specified
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            // Filter by active status
            if ($request->filled('is_active')) {
                $query->where('is_active', $request->is_active);
            }

            $photos = $query->orderBy('sort_order')->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'photos' => $photos->map(function($photo) {
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
                        'created_at' => $photo->created_at->format('d M Y H:i')
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to get hotel photos', [
                'error' => $e->getMessage(),
                'user' => Auth::user()->email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil foto hotel.'
            ], 500);
        }
    }

    /**
     * Store new hotel photo
     */
    public function storeHotelPhoto(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'nullable|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
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

            DB::beginTransaction();

            // Handle file upload
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Ensure directory exists
            $storagePath = storage_path('app/public/hotel_photos');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }
            
            $path = $image->storeAs('hotel_photos', $filename, 'public');

            // Create photo record
            $photoData = [
                'title' => $request->title,
                'description' => null,
                'image_path' => $path,
                'category' => $request->room_type_id ? 'room' : 'general',
                'room_type_id' => $request->room_type_id,
                'sort_order' => $request->sort_order ?? 0,
                'is_featured' => $request->boolean('is_featured', false),
                'is_active' => $request->boolean('is_active', true)
            ];

            $photo = HotelPhoto::create($photoData);

            DB::commit();

            Log::info('Hotel photo uploaded', [
                'photo_id' => $photo->id,
                'filename' => $filename,
                'category' => $photo->category,
                'room_type_id' => $photo->room_type_id,
                'uploaded_by' => Auth::user()->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto hotel berhasil diupload!',
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
            DB::rollback();
            
            Log::error('Failed to upload hotel photo', [
                'error' => $e->getMessage(),
                'user' => Auth::user()->email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupload foto: ' . $e->getMessage()
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

            $validator = Validator::make($request->all(), [
                'title' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
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

            DB::beginTransaction();

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

            DB::commit();

            Log::info('Hotel photo updated', [
                'photo_id' => $photo->id,
                'updated_by' => Auth::user()->email
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
            DB::rollback();
            
            Log::error('Failed to update hotel photo', [
                'photo_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate foto: ' . $e->getMessage()
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

            DB::beginTransaction();

            // Delete image file
            if ($photo->image_path && Storage::disk('public')->exists($photo->image_path)) {
                Storage::disk('public')->delete($photo->image_path);
            }

            $photoTitle = $photo->title;
            $photo->delete();

            DB::commit();

            Log::info('Hotel photo deleted', [
                'photo_id' => $id,
                'photo_title' => $photoTitle,
                'deleted_by' => Auth::user()->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto hotel berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Failed to delete hotel photo', [
                'photo_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus foto: ' . $e->getMessage()
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
            $photo->is_featured = !$photo->is_featured;
            $photo->save();

            Log::info('Hotel photo featured status toggled', [
                'photo_id' => $photo->id,
                'new_featured_status' => $photo->is_featured,
                'updated_by' => Auth::user()->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status featured foto berhasil diubah!',
                'is_featured' => $photo->is_featured
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to toggle photo featured status', [
                'photo_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status featured.'
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

            Log::info('Hotel photo status toggled', [
                'photo_id' => $photo->id,
                'new_status' => $photo->is_active ? 'active' : 'inactive',
                'updated_by' => Auth::user()->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status foto berhasil diubah!',
                'is_active' => $photo->is_active
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to toggle photo status', [
                'photo_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status foto.'
            ], 500);
        }
    }

    /**
     * Update photos sort order
     */
    public function updatePhotosOrder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'photos' => 'required|array',
                'photos.*.id' => 'required|exists:hotel_photos,id',
                'photos.*.sort_order' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            foreach ($request->photos as $photoData) {
                HotelPhoto::where('id', $photoData['id'])
                    ->update(['sort_order' => $photoData['sort_order']]);
            }

            DB::commit();

            Log::info('Hotel photos order updated', [
                'photos_count' => count($request->photos),
                'updated_by' => Auth::user()->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Urutan foto berhasil diupdate!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Failed to update photos order', [
                'error' => $e->getMessage(),
                'user' => Auth::user()->email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate urutan foto.'
            ], 500);
        }
    }
}

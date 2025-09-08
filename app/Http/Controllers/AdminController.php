<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Package;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
        $totalHotels = 0; // placeholder until Hotel CRUD is implemented
        $totalTickets = Package::count();
        
        // Get recent bookings
        $recentBookings = Booking::with(['user'])
            ->latest()
            ->limit(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalHotels',
            'totalTickets',
            'recentBookings'
        ));
    }

    public function users()
    {
        $users = User::where('role', 'user')->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'visitors', 'bookingDetails.package']);
        
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
        
        $bookings = $query->latest()->paginate(20)->withQueryString();
        
        return view('admin.bookings', compact('bookings'));
    }

    public function bookingDetail($id)
    {
        try {
            $booking = Booking::with(['user', 'visitors', 'bookingDetails.package'])->findOrFail($id);
            
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

        try {
            $booking = Booking::findOrFail($id);
            
            $oldPaymentStatus = $booking->payment_status;
            $oldCheckInStatus = $booking->check_in_status;
            
            $booking->update([
                'payment_status' => $request->payment_status,
                'check_in_status' => $request->check_in_status,
                'paid_at' => $request->payment_status === 'paid' && $oldPaymentStatus !== 'paid' ? now() : $booking->paid_at,
                'checked_in_at' => $request->check_in_status === 'checked_in' && $oldCheckInStatus !== 'checked_in' ? now() : $booking->checked_in_at
            ]);

            // Generate QR code if payment status changed to paid
            if ($request->payment_status === 'paid' && !$booking->qr_code) {
                $booking->qr_code = $booking->generateQRCode();
                $booking->save();
            }

            Log::info('Booking status updated manually', [
                'booking_id' => $id,
                'booking_code' => $booking->booking_code,
                'old_payment_status' => $oldPaymentStatus,
                'new_payment_status' => $request->payment_status,
                'old_check_in_status' => $oldCheckInStatus,
                'new_check_in_status' => $request->check_in_status,
                'updated_by' => Auth::user()->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status booking berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update booking status', [
                'booking_id' => $id,
                'error' => $e->getMessage(),
                'user' => Auth::user()->email
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui status booking.'
            ], 500);
        }
    }

    public function deleteBooking($id)
    {
        try {
            $booking = Booking::with(['bookingDetails', 'visitors'])->findOrFail($id);
            
            // Log the deletion attempt
            Log::info('Booking deletion attempt', [
                'booking_id' => $id,
                'booking_code' => $booking->booking_code,
                'booker_name' => $booking->booker_name,
                'deleted_by' => Auth::user()->email
            ]);

            // Delete related data first (cascade delete)
            $booking->bookingDetails()->delete();
            $booking->visitors()->delete();
            
            // Delete the booking
            $booking->delete();

            Log::info('Booking deleted successfully', [
                'booking_id' => $id,
                'booking_code' => $booking->booking_code
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
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
        // Placeholder view for Hotels CRUD to be implemented
        return view('admin.hotels');
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

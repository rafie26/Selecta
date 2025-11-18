<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PetugasLoketController extends Controller
{
    /**
     * Display dashboard for petugas loket
     */
    public function dashboard()
    {
        $totalPackages = Package::count();
        $totalBookings = Booking::where('booking_type', 'ticket')->count();
        $pendingBookings = Booking::where('booking_type', 'ticket')
            ->where('payment_status', 'pending')
            ->count();
        $paidBookings = Booking::where('booking_type', 'ticket')
            ->where('payment_status', 'paid')
            ->count();

        return view('petugas-loket.dashboard', compact(
            'totalPackages',
            'totalBookings',
            'pendingBookings',
            'paidBookings'
        ));
    }

    /**
     * Display ticket bookings
     */
    public function ticketBookings(Request $request)
    {
        $query = Booking::with(['bookingDetails.package', 'user'])
            ->where(function($q) {
                $q->where('booking_type', 'ticket')
                  ->orWhereNull('booking_type');
            });

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

        // Filter by package
        if ($request->has('package_id') && $request->package_id != '') {
            $query->whereHas('bookingDetails', function($q) use ($request) {
                $q->where('package_id', $request->package_id);
            });
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
        $packages = Package::where('is_active', true)->get();

        return view('petugas-loket.ticket-bookings', compact('bookings', 'packages'));
    }

    /**
     * Display packages management
     */
    public function packages(Request $request)
    {
        $query = Package::query();

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

        $packages = $query->latest()->paginate(15);

        return view('petugas-loket.packages', compact('packages'));
    }

    /**
     * Show QR Scanner
     */
    public function qrScanner()
    {
        return view('petugas-loket.qr-scanner');
    }

    /**
     * Store new package
     */
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

            Log::info('New package created by Petugas Loket', [
                'package_id' => $package->id,
                'package_name' => $package->name,
                'price' => $package->price
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Package berhasil dibuat!',
                    'package' => $package
                ]);
            }

            return redirect()->route('petugas-loket.packages')->with('success', 'Package berhasil dibuat!');

        } catch (\Exception $e) {
            Log::error('Failed to create package by Petugas Loket', [
                'error' => $e->getMessage()
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

    /**
     * Update package
     */
    public function updatePackage(Request $request, $id)
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
            $package = Package::findOrFail($id);
            
            $package->update([
                'name' => $request->name,
                'description' => $request->description ?: '',
                'price' => $request->price,
                'features' => $request->features ? array_map('trim', explode(',', $request->features)) : null,
                'badge' => $request->badge ?: '',
                'is_active' => $request->has('is_active') && in_array($request->is_active, ['on', '1', 'true', true])
            ]);

            Log::info('Package updated by Petugas Loket', [
                'package_id' => $package->id,
                'package_name' => $package->name
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Package berhasil diupdate!',
                    'package' => $package
                ]);
            }

            return redirect()->route('petugas-loket.packages')->with('success', 'Package berhasil diupdate!');

        } catch (\Exception $e) {
            Log::error('Failed to update package by Petugas Loket', [
                'package_id' => $id,
                'error' => $e->getMessage()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengupdate package.'
                ], 500);
            }

            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengupdate package.'])->withInput();
        }
    }

    /**
     * Delete package
     */
    public function deletePackage($id)
    {
        try {
            $package = Package::findOrFail($id);
            
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

            Log::info('Package deleted by Petugas Loket', [
                'package_name' => $packageName
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Package berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete package by Petugas Loket', [
                'package_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus package.'
            ], 500);
        }
    }

    /**
     * Toggle package status
     */
    public function togglePackageStatus($id)
    {
        try {
            $package = Package::findOrFail($id);
            $package->is_active = !$package->is_active;
            $package->save();

            Log::info('Package status toggled by Petugas Loket', [
                'package_id' => $package->id,
                'package_name' => $package->name,
                'new_status' => $package->is_active ? 'active' : 'inactive'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status package berhasil diubah!',
                'is_active' => $package->is_active
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to toggle package status by Petugas Loket', [
                'package_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status package.'
            ], 500);
        }
    }
}

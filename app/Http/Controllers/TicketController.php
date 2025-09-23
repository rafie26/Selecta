<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Visitor;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index()
    {
        try {
            $packages = Package::active()->get();
            
            // If no packages found, create default ones
            if ($packages->isEmpty()) {
                $this->createDefaultPackages();
                $packages = Package::active()->get();
            }
            
            // Create package mapping for JavaScript
            $packageMapping = [];
            foreach ($packages as $package) {
                $key = strtolower(str_replace('Tiket ', '', $package->name));
                $packageMapping[$key] = $package->id;
            }
            
            // Fallback mapping if no packages
            if (empty($packageMapping)) {
                $packageMapping = ['reguler' => 1, 'terusan' => 2];
            }
            
            // Get reviews for display
            $reviews = Review::with('user')->active()->latest()->take(10)->get();
            
            // Check if current user has already reviewed
            $userReview = null;
            if (Auth::check()) {
                $userReview = Review::where('user_id', Auth::id())->first();
            }
            
            return view('ticket.index', compact('packages', 'packageMapping', 'reviews', 'userReview'));
        } catch (\Exception $e) {
            // If table doesn't exist, return empty collection
            $packages = collect([]);
            $packageMapping = ['reguler' => 1, 'terusan' => 2];
            $reviews = collect([]);
            $userReview = null;
            return view('ticket.index', compact('packages', 'packageMapping', 'reviews', 'userReview'));
        }
    }

    public function book(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
            'visit_date' => 'required|date|after:today',
            'quantity' => 'required|integer|min:1|max:100',
            'booker_name' => 'required|string|max:255',
            'booker_email' => 'required|email',
            'booker_phone' => 'required|string|max:20',
        ]);

        if (!Auth::check()) {
            return response()->json([
                'error' => true,
                'message' => 'Silakan login terlebih dahulu untuk melakukan pemesanan.'
            ], 401);
        }

        $user = Auth::user();
        
        // Get package
        $package = Package::where('id', $request->package_id)->where('is_active', true)->first();
        
        if (!$package) {
            return response()->json([
                'error' => true,
                'message' => 'Paket tiket tidak ditemukan.'
            ], 404);
        }

        $quantity = $request->quantity;
        $totalAmount = $package->price * $quantity;

        DB::beginTransaction();
        try {
            // Generate booking code
            $bookingCode = 'TKT-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            
            // Create booking
            $booking = Booking::create([
                'booking_code' => $bookingCode,
                'booking_type' => 'ticket',
                'user_id' => $user->id,
                'booker_name' => $request->booker_name,
                'booker_email' => $request->booker_email,
                'booker_phone' => $request->booker_phone,
                'visit_date' => $request->visit_date,
                'total_amount' => $totalAmount,
                'payment_status' => 'pending',
                'check_in_status' => 'pending',
                'payment_method' => null,
            ]);

            // Create booking detail
            BookingDetail::create([
                'booking_id' => $booking->id,
                'package_id' => $package->id,
                'quantity' => $quantity,
                'unit_price' => $package->price,
                'subtotal' => $totalAmount,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat!',
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'total_amount' => $totalAmount,
                'package_name' => $package->name
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Ticket booking failed', [
                'error_message' => $e->getMessage(),
                'user_id' => $user->id,
                'package_id' => $request->package_id,
                'quantity' => $quantity
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat membuat booking. Silakan coba lagi.'
            ], 500);
        }
    }
    
    private function createDefaultPackages()
    {
        // Create Tiket Reguler
        Package::create([
            'name' => 'Tiket Reguler',
            'description' => 'Termasuk: Kolam renang, waterpark, taman bunga, dan fasilitas dasar lainnya',
            'price' => 50000,
            'features' => [
                'Tiket masuk',
                'Akses kolam renang',
                'Waterpark',
                'Kolam Ikan',
                'Akuarium',
                'Taman Bunga',
                'Dino Ranch',
                'Asuransi kecelakaan'
            ],
            'badge' => '',
            'is_active' => true
        ]);

        // Create Tiket Terusan
        Package::create([
            'name' => 'Tiket Terusan',
            'description' => 'Akses ke semua wahana dan fasilitas Taman Rekreasi Selecta termasuk tiket masuk',
            'price' => 80000,
            'features' => [
                '1x Tiket Masuk ke Taman Rekreasi Selecta untuk 1 Pengunjung',
                '1x Tiket Masuk ke Dino Ranch untuk 1 Pengunjung',
                '1x Tiket Masuk ke Bioskop 4D untuk 1 Pengunjung',
                '1x Tiket Masuk ke Mobil Ayun untuk 1 Pengunjung',
                '1x Tiket Masuk ke Mini Bumper Car untuk 1 Pengunjung',
                '1x Tiket Masuk ke Paddle Boat untuk 1 Pengunjung',
                '1x Akses ke Bianglala untuk 1 Pengunjung',
                '1x Akses ke Dino Ride untuk 1 Pengunjung',
                '1x Akses ke Sky Bike untuk 1 Pengunjung',
                '1x Akses ke Garden Tram untuk 1 Pengunjung',
                '1x Akses ke Kolam Renang untuk 1 Pengunjung',
                '1x Akses ke Waterpark untuk 1 Pengunjung',
                '1x Akses ke Kolam Ikan untuk 1 Pengunjung',
                '1x Akses ke Taman Lumut untuk 1 Pengunjung',
                '1x Akses ke Taman Bunga untuk 1 Pengunjung',
                '1x Akses ke Tagada Disco untuk 1 Pengunjung'
            ],
            'badge' => 'Premium',
            'is_active' => true
        ]);
    }

    /**
     * Store a new review
     */
    public function storeReview(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => true,
                'message' => 'Silakan login terlebih dahulu untuk memberikan review.'
            ], 401);
        }

        $user = Auth::user();

        // Check if user already has a review
        $existingReview = Review::where('user_id', $user->id)->first();
        if ($existingReview) {
            return response()->json([
                'error' => true,
                'message' => 'Anda sudah memberikan review sebelumnya. Setiap akun hanya dapat memberikan 1 review.'
            ], 400);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $imagePath = null;
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = 'review_' . $user->id . '_' . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('reviews', $imageName, 'public');
            }

            // Create review
            $review = Review::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'image_path' => $imagePath,
                'is_active' => true
            ]);

            DB::commit();

            // Load the review with user relationship for response
            $review->load('user');

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil ditambahkan!',
                'review' => [
                    'id' => $review->id,
                    'name' => $review->name,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'image_url' => $review->image_url,
                    'created_at' => $review->created_at->format('d M Y'),
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Review creation failed', [
                'error_message' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat menyimpan review. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Update existing review
     */
    public function updateReview(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => true,
                'message' => 'Silakan login terlebih dahulu.'
            ], 401);
        }

        $user = Auth::user();
        $review = Review::where('user_id', $user->id)->first();

        if (!$review) {
            return response()->json([
                'error' => true,
                'message' => 'Review tidak ditemukan.'
            ], 404);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_image' => 'nullable|boolean'
        ]);

        DB::beginTransaction();
        try {
            $imagePath = $review->image_path;
            
            // Handle image removal
            if ($request->has('remove_image') && $request->remove_image) {
                // User wants to remove the image
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = null;
            }
            // Handle new image upload
            elseif ($request->hasFile('image')) {
                // Delete old image if exists
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                
                $image = $request->file('image');
                $imageName = 'review_' . $user->id . '_' . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('reviews', $imageName, 'public');
            }
            // If no file and no remove_image flag, keep existing image

            // Update review
            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'image_path' => $imagePath,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil diperbarui!',
                'review' => [
                    'id' => $review->id,
                    'name' => $review->name,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'image_url' => $review->image_url,
                    'created_at' => $review->created_at->format('d M Y'),
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Review update failed', [
                'error_message' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat memperbarui review. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Delete review
     */
    public function deleteReview($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => true,
                'message' => 'Silakan login terlebih dahulu.'
            ], 401);
        }

        $user = Auth::user();
        $review = Review::where('user_id', $user->id)->where('id', $id)->first();

        if (!$review) {
            return response()->json([
                'error' => true,
                'message' => 'Review tidak ditemukan.'
            ], 404);
        }

        try {
            // Delete image if exists
            if ($review->image_path && Storage::disk('public')->exists($review->image_path)) {
                Storage::disk('public')->delete($review->image_path);
            }

            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            Log::error('Review deletion failed', [
                'error_message' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan saat menghapus review. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Get all reviews for AJAX loading
     */
    public function getReviews()
    {
        try {
            $reviews = Review::with('user')->active()->latest()->get();
            
            $reviewsData = $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'name' => $review->name,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'image_url' => $review->image_url,
                    'created_at' => $review->created_at->format('d M Y'),
                ];
            });

            return response()->json([
                'success' => true,
                'reviews' => $reviewsData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gagal memuat review.'
            ], 500);
        }
    }
}
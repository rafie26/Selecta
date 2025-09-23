<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all'); // all, ticket, hotel
        
        // Base query - show all bookings for debugging, then filter properly
        $query = Booking::with(['bookingDetails.package'])
            ->where('user_id', $user->id)
            ->latest();

        // Apply filter
        if ($filter === 'ticket') {
            $query->where(function($q) {
                $q->where('booking_type', 'ticket')
                  ->orWhereNull('booking_type');
            });
        } elseif ($filter === 'hotel') {
            $query->where('booking_type', 'hotel');
        }

        $bookings = $query->paginate(10);

        return view('booking-history.index', compact('bookings', 'filter'));
    }

    public function show($id)
    {
        $booking = Booking::with(['bookingDetails.package', 'visitors'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('booking-history.show', compact('booking'));
    }

    public function updateCheckInTime(Request $request, $id)
    {
        $request->validate([
            'check_in_time' => 'required|date_format:H:i'
        ]);

        $booking = Booking::where('user_id', Auth::id())
            ->where('payment_status', 'paid')
            ->findOrFail($id);

        $booking->update([
            'check_in_time' => $request->check_in_time,
            'check_in_status' => 'checked_in',
            'checked_in_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Waktu check-in berhasil dicatat!'
        ]);
    }

    public function updateCheckOutTime(Request $request, $id)
    {
        $request->validate([
            'check_out_time' => 'required|date_format:H:i'
        ]);

        $booking = Booking::where('user_id', Auth::id())
            ->where('payment_status', 'paid')
            ->findOrFail($id);

        $booking->update([
            'check_out_time' => $request->check_out_time
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Waktu check-out berhasil dicatat!'
        ]);
    }
}

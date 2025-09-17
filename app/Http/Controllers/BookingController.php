<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\RoomBooking;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        return view('booking.index');
    }

    public function store(Request $request)
    {
        // Validasi & simpan booking
        // Booking::create($request->all());
        return redirect()->back()->with('success', 'Booking berhasil dikirim!');
    }

    public function myBookings()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Get user's bookings with related data
        $bookings = Booking::with(['user', 'visitors', 'bookingDetails.package', 'roomBookings.roomType'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('booking.my-bookings', compact('bookings'));
    }

    public function show($id)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $booking = Booking::with(['user', 'visitors', 'bookingDetails.package', 'roomBookings.roomType'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('booking.detail', compact('booking'));
    }
}

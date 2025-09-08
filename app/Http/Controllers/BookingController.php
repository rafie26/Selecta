<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}

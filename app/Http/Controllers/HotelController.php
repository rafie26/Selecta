<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = []; // nanti ambil dari DB
        return view('hotels.index', compact('hotels'));
    }

    public function show($id)
    {
        $hotel = null; // ambil data hotel berdasarkan ID
        return view('hotels.show', compact('hotel'));
    }
}

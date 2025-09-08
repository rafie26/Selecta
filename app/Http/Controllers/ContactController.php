<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function store(Request $request)
    {
        // Validasi & simpan pesan
        // Contact::create($request->all());
        return redirect()->back()->with('success', 'Pesan berhasil dikirim!');
    }
}

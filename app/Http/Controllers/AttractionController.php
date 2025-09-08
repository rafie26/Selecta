<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttractionController extends Controller
{
    public function index()
    {
        $attractions = []; 
        return view('attractions.index', compact('attractions'));
    }

    public function show($id)
    {
        $attraction = null;
        return view('attractions.show', compact('attraction'));
    }
}
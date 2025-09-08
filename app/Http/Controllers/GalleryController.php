<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $photos = []; 
        return view('gallery.index', compact('photos'));
    }
}

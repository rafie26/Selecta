<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $photos = Gallery::where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('photo_date')
            ->orderByDesc('created_at')
            ->get();

        return view('gallery.index', compact('photos'));
    }
}

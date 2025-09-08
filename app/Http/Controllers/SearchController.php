<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('q');
        $results = collect();

        if ($keyword) {
            $hotels = DB::table('hotels')
                ->where('name', 'like', "%{$keyword}%")
                ->select('id', 'name as title')
                ->get()
                ->map(function ($item) {
                    $item->type = 'Hotel';
                    return $item;
                });

            $restaurants = DB::table('restaurants')
                ->where('name', 'like', "%{$keyword}%")
                ->select('id', 'name as title')
                ->get()
                ->map(function ($item) {
                    $item->type = 'Restoran';
                    return $item;
                });

            $tickets = DB::table('tickets')
                ->where('title', 'like', "%{$keyword}%")
                ->select('id', 'title')
                ->get()
                ->map(function ($item) {
                    $item->type = 'Tiket';
                    return $item;
                });

            $galleries = DB::table('galleries')
                ->where('title', 'like', "%{$keyword}%")
                ->select('id', 'title')
                ->get()
                ->map(function ($item) {
                    $item->type = 'Galeri';
                    return $item;
                });

            $attractions = DB::table('attractions')
                ->where('name', 'like', "%{$keyword}%")
                ->select('id', 'name as title')
                ->get()
                ->map(function ($item) {
                    $item->type = 'Attraction';
                    return $item;
                });

            $results = $hotels
                ->merge($restaurants)
                ->merge($tickets)
                ->merge($galleries)
                ->merge($attractions);
        }

        return view('search.index', compact('keyword', 'results'));
    }
}

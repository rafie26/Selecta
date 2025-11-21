<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RoomType;
use App\Models\Restaurant;
use App\Models\Package;
use App\Models\Gallery;
use App\Models\TopAttraction;
use App\Models\MenuItem;

class SearchController extends Controller
{
    /**
     * Halaman hasil pencarian global dari landing page.
     */
    public function index(Request $request)
    {
        $keyword = trim((string) $request->input('q'));

        $results = [
            'hotels' => collect(),
            'tickets' => collect(),
            'restaurants' => collect(),
            'galleries' => collect(),
            'attractions' => collect(),
        ];

        if ($keyword !== '') {
            // Hotel: pakai RoomType (tipe kamar) sebagai representasi hotel
            $results['hotels'] = RoomType::query()
                ->where('is_active', true)
                ->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%")
                      // amenities disimpan sebagai JSON, kita pakai whereRaw sederhana
                      ->orWhereRaw('CAST(amenities AS CHAR) LIKE ?', ["%{$keyword}%"]);
                })
                ->select('id', 'name as title', 'description', 'price_per_night')
                ->get();

            // Tiket: pakai Package (nama, deskripsi, fitur JSON, badge)
            $results['tickets'] = Package::query()
                ->where('is_active', true)
                ->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%")
                      ->orWhere('badge', 'like', "%{$keyword}%")
                      ->orWhereRaw('CAST(features AS CHAR) LIKE ?', ["%{$keyword}%"]);
                })
                ->select('id', 'name as title', 'description', 'price')
                ->get();

            // Restoran: cari di nama, deskripsi, jenis masakan, fitur JSON, jam operasional, lokasi
            $restaurantQuery = Restaurant::query()
                ->where('is_active', true)
                ->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%")
                      ->orWhere('cuisine_type', 'like', "%{$keyword}%")
                      ->orWhere('operating_hours', 'like', "%{$keyword}%")
                      ->orWhere('location', 'like', "%{$keyword}%")
                      ->orWhereRaw('CAST(features AS CHAR) LIKE ?', ["%{$keyword}%"]);
                });

            // Tambahkan restoran yang match dari menu item (nama/desc menu cocok)
            $restaurantIdsFromMenu = MenuItem::query()
                ->where('is_active', true)
                ->where(function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%")
                      ->orWhere('category', 'like', "%{$keyword}%");
                })
                ->distinct()
                ->pluck('restaurant_id')
                ->filter()
                ->toArray();

            if (!empty($restaurantIdsFromMenu)) {
                $restaurantQuery->orWhereIn('id', $restaurantIdsFromMenu);
            }

            $results['restaurants'] = $restaurantQuery
                ->select('id', 'name as title', 'description', 'slug', 'cuisine_type')
                ->get();

            // Galeri: cari di judul, dan jika perlu di path gambar
            $results['galleries'] = Gallery::query()
                ->where('is_active', true)
                ->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                      ->orWhere('image_path', 'like', "%{$keyword}%");
                })
                ->select('id', 'title', 'image_path')
                ->get();

            // Wahana (TopAttraction): judul, lokasi, deskripsi
            $results['attractions'] = TopAttraction::query()
                ->where('is_active', true)
                ->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                      ->orWhere('location', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%");
                })
                ->select('id', 'title', 'location', 'description', 'image_path')
                ->get();
        }

        return view('search.index', [
            'keyword' => $keyword,
            'results' => $results,
        ]);
    }

    /**
     * Endpoint kecil untuk suggestion (dipakai jika nanti butuh autocomplete).
     */
    public function suggestions(Request $request)
    {
        $keyword = trim((string) $request->input('q'));

        if ($keyword === '') {
            return response()->json([]);
        }

        $limit = 5;

        $hotelSuggestions = RoomType::query()
            ->where('is_active', true)
            ->where('name', 'like', "%{$keyword}%")
            ->limit($limit)
            ->pluck('name')
            ->toArray();

        $ticketSuggestions = Package::query()
            ->where('is_active', true)
            ->where('name', 'like', "%{$keyword}%")
            ->limit($limit)
            ->pluck('name')
            ->toArray();

        $restaurantSuggestions = Restaurant::query()
            ->where('is_active', true)
            ->where('name', 'like', "%{$keyword}%")
            ->limit($limit)
            ->pluck('name')
            ->toArray();

        $attractionSuggestions = TopAttraction::query()
            ->where('is_active', true)
            ->where('title', 'like', "%{$keyword}%")
            ->limit($limit)
            ->pluck('title')
            ->toArray();

        $titles = array_values(array_unique(array_merge(
            $hotelSuggestions,
            $ticketSuggestions,
            $restaurantSuggestions,
            $attractionSuggestions
        )));

        return response()->json($titles);
    }
}

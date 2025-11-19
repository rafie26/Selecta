<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = \App\Models\Restaurant::with('activeMenuItems')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        $menuData = [];

        foreach ($restaurants as $restaurant) {
            $slug = $restaurant->slug;
            $menuData[$slug] = [
                'name' => $restaurant->name,
                'items' => $restaurant->activeMenuItems->map(function ($item) {
                    return [
                        'name' => $item->name,
                        'description' => $item->description,
                        'price' => 'Rp ' . number_format((float) $item->price, 0, ',', '.'),
                        'category' => $item->category,
                        'image' => $item->image_url ?? '/images/heroresto.png',
                    ];
                })->values()->all(),
            ];
        }

        return view('restaurant.index', compact('restaurants', 'menuData'));
    }

    public function show($slug)
    {
        $restaurant = \App\Models\Restaurant::where('slug', $slug)->firstOrFail();

        return redirect()->route('restaurants.index');
    }
}
<?php
namespace App\View\Components;
use Illuminate\View\Component;

class Navbar extends Component
{
    public function render()
    {
        $navItems = [
            ['name' => 'Home', 'url' => route('home'), 'route' => 'home'],
            ['name' => 'Hotels', 'url' => route('hotels.index'), 'route' => 'hotels.*'],
            ['name' => 'Restaurants', 'url' => route('restaurants.index'), 'route' => 'restaurants.*'],
            ['name' => 'Gallery', 'url' => route('gallery.index'), 'route' => 'gallery.*'],
            ['name' => 'Contact', 'url' => route('contact.index'), 'route' => 'contact.*'],
        ];
        
        return view('components.navbar', compact('navItems'));
    }
}
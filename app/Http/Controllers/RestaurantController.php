<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    private $restaurantData = [
        'bahagia' => [
            'name' => 'Restoran Bahagia',
            'description' => 'Restoran keluarga dengan suasana hangat dan menu tradisional Indonesia',
            'type' => 'Traditional Indonesian',
            'image' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            'features' => ['Masakan Tradisional', 'Family Friendly', 'Halal']
        ],
        'asri' => [
            'name' => 'Restoran Asri',
            'description' => 'Nikmati pengalaman bersantap di tengah taman yang asri dengan menu fusion Asia-Western',
            'type' => 'Asian Fusion',
            'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            'features' => ['Asian Fusion', 'Outdoor Dining', 'Instagram-able']
        ],
        'cantik' => [
            'name' => 'Restoran Cantik',
            'description' => 'Fine dining experience dengan menu internasional dan wine selection terbaik',
            'type' => 'Fine Dining',
            'image' => 'https://images.unsplash.com/photo-1552566090-ba4061f84a8e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
            'features' => ['Fine Dining', 'Wine Bar', 'Romantic']
        ]
    ];

    private $menuData = [
        'bahagia' => [
            [
                'name' => 'Gudeg Jogja',
                'description' => 'Gudeg khas Jogja dengan nangka muda, telur, dan opor ayam kampung',
                'price' => 'Rp 45.000',
                'category' => 'main',
                'image' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ],
            [
                'name' => 'Rawon Setan',
                'description' => 'Rawon hitam khas Jawa Timur dengan daging sapi empuk',
                'price' => 'Rp 50.000',
                'category' => 'main',
                'image' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ],
            [
                'name' => 'Soto Ayam Lamongan',
                'description' => 'Soto ayam dengan kuah bening, dilengkapi kerupuk dan sambal',
                'price' => 'Rp 35.000',
                'category' => 'main',
                'image' => 'https://images.unsplash.com/photo-1623341214825-9f4f963727da?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ],
            [
                'name' => 'Kerak Telor',
                'description' => 'Kerak telor tradisional dengan telur bebek dan beras ketan',
                'price' => 'Rp 25.000',
                'category' => 'appetizer',
                'image' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ],
            [
                'name' => 'Es Dawet Ayu',
                'description' => 'Minuman tradisional dengan santan dan gula jawa',
                'price' => 'Rp 15.000',
                'category' => 'beverage',
                'image' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ],
            [
                'name' => 'Klepon',
                'description' => 'Kue tradisional dengan isi gula jawa dan kelapa parut',
                'price' => 'Rp 20.000',
                'category' => 'dessert',
                'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ]
        ],
        'asri' => [
            [
                'name' => 'Nasi Goreng Seafood',
                'description' => 'Nasi goreng dengan udang, cumi, dan ikan segar dari laut selatan',
                'price' => 'Rp 55.000',
                'category' => 'main',
                'image' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ],
            [
                'name' => 'Ayam Bakar Madu',
                'description' => 'Ayam bakar dengan marinasi madu dan rempah pilihan',
                'price' => 'Rp 48.000',
                'category' => 'main',
                'image' => 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ],
            [
                'name' => 'Gado-gado Betawi',
                'description' => 'Salad sayuran dengan bumbu kacang khas Betawi',
                'price' => 'Rp 32.000',
                'category' => 'appetizer',
                'image' => 'https://images.unsplash.com/photo-1565299507177-b0ac66763828?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ],
            [
                'name' => 'Thai Green Curry',
                'description' => 'Kari hijau Thailand dengan ayam dan sayuran segar',
                'price' => 'Rp 58.000',
                'category' => 'main',
                'image' => 'https://images.unsplash.com/photo-1455619452474-d2be8b1e70cd?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ],
            [
                'name' => 'Es Teh Tawar',
                'description' => 'Es teh tawar segar dengan daun teh pilihan',
                'price' => 'Rp 12.000',
                'category' => 'beverage',
                'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ],
            [
                'name' => 'Puding Coklat',
                'description' => 'Puding coklat lembut dengan saus karamel',
                'price' => 'Rp 25.000',
                'category' => 'dessert',
                'image' => 'https://images.unsplash.com/photo-1570197788417-0e82375c9371?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ]
        ],
        'cantik' => [
            [
                'name' => 'Beef Wellington',
                'description' => 'Daging sapi premium dengan pastry berlapis, disajikan dengan red wine jus',
                'price' => 'Rp 285.000',
                'category' => 'main',
                'image' => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ],
            [
                'name' => 'Lobster Thermidor',
                'description' => 'Lobster segar dengan saus mentega dan keju, dipanggang sempurna',
                'price' => 'Rp 320.000',
                'category' => 'main',
                'image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ],
            [
                'name' => 'Foie Gras',
                'description' => 'Foie gras premium dengan brioche toast dan berry compote',
                'price' => 'Rp 180.000',
                'category' => 'appetizer',
                'image' => 'https://images.unsplash.com/photo-1544148103-0773bf10d330?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ],
            [
                'name' => 'Truffle Risotto',
                'description' => 'Risotto creamy dengan truffle hitam dan parmesan aged',
                'price' => 'Rp 165.000',
                'category' => 'main',
                'image' => 'https://images.unsplash.com/photo-1476124369491-e7addf5db371?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ],
            [
                'name' => 'Dom Pérignon',
                'description' => 'Champagne premium Dom Pérignon vintage',
                'price' => 'Rp 2.500.000',
                'category' => 'beverage',
                'image' => 'https://images.unsplash.com/photo-1558618047-3c8c76ca7d13?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ],
            [
                'name' => 'Chocolate Soufflé',
                'description' => 'Soufflé coklat hangat dengan vanilla ice cream',
                'price' => 'Rp 85.000',
                'category' => 'dessert',
                'image' => 'https://images.unsplash.com/photo-1551024506-0bccd828d307?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'
            ]
        ]
    ];

    public function index()
    {
        $restaurants = $this->restaurantData;
        return view('restaurant.index', compact('restaurants'));
    }

    public function show($type)
    {
        if (!array_key_exists($type, $this->restaurantData)) {
            abort(404);
        }

        $restaurant = $this->restaurantData[$type];
        $menu = $this->menuData[$type];

        return view('restaurant.show', compact('restaurant', 'menu', 'type'));
    }

    public function menu($type)
    {
        if (!array_key_exists($type, $this->menuData)) {
            return response()->json(['error' => 'Restaurant not found'], 404);
        }

        $menu = $this->menuData[$type];
        return response()->json([
            'restaurant' => $this->restaurantData[$type]['name'],
            'menu' => $menu
        ]);
    }
}
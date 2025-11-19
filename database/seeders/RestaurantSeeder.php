<?php

namespace Database\Seeders;

 use App\Models\MenuItem;
 use App\Models\Restaurant;
 use Illuminate\Database\Seeder;

 class RestaurantSeeder extends Seeder
 {
     /**
      * Run the database seeds.
      */
     public function run(): void
     {
         if (Restaurant::count() > 0) {
             return;
         }

         $restaurants = [
             'bahagia' => [
                 'name' => 'Restoran Bahagia',
                 'description' => 'Restoran yang menyajikan makanan khas Jawa dengan suasana akrab dan tradisional.',
                 'image_path' => '/images/restobahagia.jpeg',
                 'cuisine_type' => 'Masakan Jawa',
                 'operating_hours' => '08:00 - 21:00 WIB',
                 'location' => 'Jl. Raya Selecta No. 1, Batu, Malang',
                 'features' => ['Masakan Jawa', 'Family Friendly', 'Halal'],
                 'menus' => [
                     [
                         'name' => 'Sop Buntut Istimewa',
                         'description' => 'Sup buntut dengan kuah bening kaya rempah, dilengkapi wortel dan kentang segar yang empuk.',
                         'price' => 45000,
                         'category' => 'makanan',
                         'image_path' => '/images/sopbuntut.jpeg',
                     ],
                     [
                         'name' => 'Rawon Spesial',
                         'description' => 'Rawon hitam khas Jawa Timur dengan daging sapi empuk, keluak khas, dan sambal terasi pedas.',
                         'price' => 50000,
                         'category' => 'makanan',
                         'image_path' => '/images/rawon.jpeg',
                     ],
                     [
                         'name' => 'Soto Ayam Istimewa',
                         'description' => 'Soto ayam dengan kuah bening gurih, ayam kampung, tauge, telur rebus, dan kerupuk renyah.',
                         'price' => 35000,
                         'category' => 'makanan',
                         'image_path' => '/images/soto.jpeg',
                     ],
                     [
                         'name' => 'Ginseng Coffee',
                         'description' => 'Kopi nikmat dengan ekstrak ginseng premium, memberikan energi dan kesegaran alami.',
                         'price' => 35000,
                         'category' => 'minuman',
                         'image_path' => '/images/ginseng.jpeg',
                     ],
                     [
                         'name' => 'Wedang Jahe',
                         'description' => 'Minuman tradisional jahe hangat dengan gula aren dan rempah pilihan, cocok untuk cuaca dingin.',
                         'price' => 35000,
                         'category' => 'minuman',
                         'image_path' => '/images/wedang.jpeg',
                     ],
                     [
                         'name' => 'Kopi Tubruk',
                         'description' => 'Kopi tradisional dengan ampas, diseduh dengan gula jawa, memberikan cita rasa khas Indonesia.',
                         'price' => 35000,
                         'category' => 'minuman',
                         'image_path' => '/images/kopitubruk.jpeg',
                     ],
                 ],
             ],
             'asri' => [
                 'name' => 'Restoran Asri',
                 'description' => 'Restoran dengan vibes seperti restoran di China dengan suasana hangat dan nyaman.',
                 'image_path' => '/images/restoasri.jpeg',
                 'cuisine_type' => 'Chinese Cuisine',
                 'operating_hours' => '10:00 - 23:00 WIB',
                 'location' => 'Jl. Raya Selecta No. 1, Batu, Malang',
                 'features' => ['Chinese Cuisine', 'Suasana Hangat', 'Pelayanan Cepat'],
                 'menus' => [
                     [
                         'name' => 'Cwimie Ayam Spesial',
                         'description' => 'Mi kuah dengan potongan ayam kampung empuk, sayuran segar, dan kuah kaldu yang gurih.',
                         'price' => 55000,
                         'category' => 'makanan',
                         'image_path' => '/images/cwimie.jpeg',
                     ],
                     [
                         'name' => 'Nasi Goreng Hongkong',
                         'description' => 'Nasi goreng ala Hongkong dengan telur mata sapi, acar timun, dan bumbu kecap manis khas.',
                         'price' => 48000,
                         'category' => 'makanan',
                         'image_path' => '/images/nasgor.jpeg',
                     ],
                     [
                         'name' => 'Mie Ayam',
                         'description' => 'Mi kuah dengan bakso sapi pilihan, pangsit goreng renyah, dan sayuran hijau segar.',
                         'price' => 32000,
                         'category' => 'makanan',
                         'image_path' => '/images/mieayam.jpeg',
                     ],
                     [
                         'name' => 'Cappucino',
                         'description' => 'Kopi cappucino dengan foam susu yang lembut dan aroma kopi yang kuat dan nikmat.',
                         'price' => 32000,
                         'category' => 'minuman',
                         'image_path' => '/images/cappucino.jpeg',
                     ],
                     [
                         'name' => 'Jus Sirsak',
                         'description' => 'Jus sirsak segar dengan daging buah asli, manis alami dan menyegarkan.',
                         'price' => 32000,
                         'category' => 'minuman',
                         'image_path' => '/images/jussirsak.jpeg',
                     ],
                     [
                         'name' => 'Soda Gembira',
                         'description' => 'Minuman bersoda segar dengan sirup buah dan susu kental manis, cocok untuk anak-anak.',
                         'price' => 32000,
                         'category' => 'minuman',
                         'image_path' => '/images/soda.jpeg',
                     ],
                 ],
             ],
             'cantik' => [
                 'name' => 'Restoran Cantik',
                 'description' => 'Restoran yang menyajikan makanan khas bakaran dengan suasana santai dan terbuka.',
                 'image_path' => '/images/restocantik.jpeg',
                 'cuisine_type' => 'Makanan Bakar',
                 'operating_hours' => '11:00 - 23:00 WIB',
                 'location' => 'Jl. Raya Selecta No. 1, Batu, Malang',
                 'features' => ['Makanan Bakar', 'Outdoor Dining', 'Suasana Santai'],
                 'menus' => [
                     [
                         'name' => 'Gurami Asam Manis',
                         'description' => 'Gurami goreng segar dengan saus asam manis, nanas, dan sayuran renyah yang menggugah selera.',
                         'price' => 185000,
                         'category' => 'makanan',
                         'image_path' => '/images/guramiasam.jpeg',
                     ],
                     [
                         'name' => 'Sate Kelinci Spesial',
                         'description' => 'Sate kelinci bakar dengan bumbu kacang khas dan lalapan segar, cita rasa unik dan lezat.',
                         'price' => 220000,
                         'category' => 'makanan',
                         'image_path' => '/images/satekelinci.jpeg',
                     ],
                     [
                         'name' => 'Gurami Goreng',
                         'description' => 'Gurami goreng crispy dengan sambal terasi pedas dan lalapan mentimun yang segar.',
                         'price' => 165000,
                         'category' => 'makanan',
                         'image_path' => '/images/guramigoreng.jpeg',
                     ],
                     [
                         'name' => 'Es Jeruk',
                         'description' => 'Minuman jeruk segar dengan es batu dan gula sesuai selera, menyegarkan di cuaca panas.',
                         'price' => 165000,
                         'category' => 'minuman',
                         'image_path' => '/images/jeruk.jpeg',
                     ],
                     [
                         'name' => 'Jus Strawberry',
                         'description' => 'Jus strawberry segar dengan buah asli dan susu, manis dan creamy yang menyegarkan.',
                         'price' => 165000,
                         'category' => 'minuman',
                         'image_path' => '/images/jusstrawberry.jpeg',
                     ],
                     [
                         'name' => 'Es Teh',
                         'description' => 'Es teh manis tradisional dengan teh pilihan dan gula yang pas, cocok menemani makanan.',
                         'price' => 165000,
                         'category' => 'minuman',
                         'image_path' => '/images/esteh.jpeg',
                     ],
                 ],
             ],
         ];

         foreach ($restaurants as $slug => $data) {
             $menus = $data['menus'] ?? [];
             unset($data['menus']);

             $restaurant = Restaurant::create([
                 'name' => $data['name'],
                 'slug' => $slug,
                 'description' => $data['description'] ?? null,
                 'image_path' => $data['image_path'] ?? null,
                 'cuisine_type' => $data['cuisine_type'] ?? null,
                 'features' => $data['features'] ?? null,
                 'operating_hours' => $data['operating_hours'] ?? null,
                 'location' => $data['location'] ?? null,
                 'is_active' => true,
             ]);

             foreach ($menus as $index => $menuData) {
                 MenuItem::create([
                     'restaurant_id' => $restaurant->id,
                     'name' => $menuData['name'],
                     'description' => $menuData['description'] ?? null,
                     'image_path' => $menuData['image_path'] ?? null,
                     'category' => $menuData['category'] ?? 'makanan',
                     'price' => $menuData['price'] ?? 0,
                     'is_active' => true,
                     'sort_order' => $index,
                 ]);
             }
         }
     }
 }


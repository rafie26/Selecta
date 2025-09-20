<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $roomTypes = [
            [
                'name' => 'Suite',
                'description' => 'Suite terbaik hotel dengan 2 kamar tidur terpisah, ruang tamu mewah, dapur lengkap, dan pemandangan panorama terbaik. Dilengkapi butler service dan fasilitas VIP eksklusif.',
                'price_per_night' => 850000,
                'max_occupancy' => 6,
                'total_rooms' => 1,
                'available_rooms' => 1,
                'amenities' => [
                    '3 AC Central Premium',
                    'WiFi Gratis Unlimited',
                    '4 TV LED Premium (65" + 3x43")',
                    '2 Kamar Mandi Mewah + Jacuzzi',
                    'Full Kitchen + Wine Cooler',
                    'Dining Room untuk 8 orang',
                    'Living Room + Entertainment System',
                    'Private Terrace 20m²',
                    'Butler Service 24/7',
                    'Premium Bar Setup',
                    'High-end Audio System',
                    'Private Elevator Access',
                    'Luxury Toiletries',
                    'Daily Fruit Basket'
                ],
                'images' => [
                    '/images/suite1.jpeg',
                    '/images/suite2.jpeg',
                    '/images/suite3.jpeg',
                    '/images/suite4.jpeg'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Executive',
                'description' => 'Kamar executive mewah dengan ruang tamu terpisah, area kerja yang luas, dan fasilitas premium. Ideal untuk business traveler atau small family yang mengutamakan kenyamanan dan privasi.',
                'price_per_night' => 450000,
                'max_occupancy' => 4,
                'total_rooms' => 4,
                'available_rooms' => 4,
                'amenities' => [
                    'AC Central dengan Kontrol Individual',
                    'WiFi Gratis 200 Mbps',
                    '2 TV LED (43" + 32")',
                    'Kamar Mandi Premium + Bathtub Jacuzzi',
                    'Living Room Terpisah',
                    'Minibar Premium',
                    'Nespresso Machine',
                    'Executive Desk + Kursi Ergonomis',
                    'Balkon Luas',
                    'Walk-in Closet',
                    'Premium Toiletries',
                    'Bathrobes & Slippers'
                ],
                'images' => [
                    '/images/executive1.jpeg',
                    '/images/executive2.jpeg',
                    '/images/executive3.jpeg',
                    '/images/executive4.jpeg'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Deluxe',
                'description' => 'Kamar deluxe dengan pemandangan taman yang indah dan area duduk yang nyaman. Lebih luas dengan pilihan konfigurasi tempat tidur sesuai kebutuhan.',
                'price_per_night' => 280000,
                'max_occupancy' => 3,
                'total_rooms' => 6,
                'available_rooms' => 6,
                'amenities' => [
                    'AC Central',
                    'WiFi Gratis 100 Mbps',
                    'TV LED 43" + Premium Channel',
                    'Kamar Mandi dengan Bathtub',
                    'Kulkas Mini + Minibar',
                    'Tea/Coffee Station',
                    'Sofa + Meja Kerja',
                    'Balkon Pribadi',
                    'Safe Deposit Box',
                    'Handuk Premium & Toiletries'
                ],
                'images' => [
                    '/images/deluxe1.jpeg',
                    '/images/deluxe2.jpeg',
                    '/images/deluxe3.jpeg',
                    '/images/deluxe4.jpeg'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Family',
                'description' => 'Kamar family terluas dengan 2 kamar tidur terpisah, ruang bermain anak, dan fasilitas keluarga lengkap. Perfect untuk extended family atau grup dengan anak-anak.',
                'price_per_night' => 650000,
                'max_occupancy' => 6,
                'total_rooms' => 3,
                'available_rooms' => 3,
                'amenities' => [
                    '2 AC Central',
                    'WiFi Gratis 200 Mbps',
                    '3 TV LED (55" + 2x32")',
                    '2 Kamar Mandi Lengkap',
                    'Kids Play Area',
                    'Kitchenette + Kulkas Besar',
                    'Dining Table untuk 6 orang',
                    'Living Room Luas',
                    '2 Balkon',
                    'Baby Cot (atas permintaan)',
                    'Kids Amenities',
                    'Family Games',
                    'Microwave',
                    'Washing Machine'
                ],
                'images' => [
                    '/images/family1.jpeg',
                    '/images/family2.jpeg',
                    '/images/family3.jpeg',
                    '/images/family4.jpeg'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Exclusive',
                'description' => 'Kamar exclusive dengan desain premium dan fasilitas eksklusif. Dilengkapi dengan area duduk yang nyaman dan pemandangan terbaik dari hotel.',
                'price_per_night' => 380000,
                'max_occupancy' => 3,
                'total_rooms' => 5,
                'available_rooms' => 5,
                'amenities' => [
                    'AC Central Premium',
                    'WiFi Gratis 150 Mbps',
                    'TV LED 50" + Premium Channel',
                    'Kamar Mandi Premium + Rain Shower',
                    'Minibar Premium',
                    'Nespresso Machine',
                    'Reading Corner',
                    'Balkon Premium',
                    'Safe Deposit Box',
                    'Luxury Toiletries',
                    'Welcome Amenities'
                ],
                'images' => [
                    '/images/exclusive1.jpeg',
                    '/images/exclusive2.jpeg',
                    '/images/exclusive3.jpeg',
                    '/images/exclusive4.jpeg'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Cottage I',
                'description' => 'Cottage dengan arsitektur tradisional yang dipadukan dengan fasilitas modern. Terletak di area terpisah dengan suasana privat dan tenang, dikelilingi taman hijau.',
                'price_per_night' => 320000,
                'max_occupancy' => 4,
                'total_rooms' => 8,
                'available_rooms' => 8,
                'amenities' => [
                    'AC Central',
                    'WiFi Gratis 100 Mbps',
                    'TV LED 43"',
                    'Kamar Mandi dengan Bathtub',
                    'Mini Kitchen',
                    'Dining Area',
                    'Living Room',
                    'Private Garden',
                    'Outdoor Seating',
                    'BBQ Area',
                    'Kulkas Besar',
                    'Tea/Coffee Maker',
                    'Traditional Decor'
                ],
                'images' => [
                    '/images/cottage1.jpeg',
                    '/images/cottage2.jpeg',
                    '/images/cottage3.jpeg'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Cottage II',
                'description' => 'Cottage premium dengan area lebih luas dan fasilitas lebih lengkap. Ideal untuk family retreat atau group vacation dengan privasi maksimal di tengah alam.',
                'price_per_night' => 420000,
                'max_occupancy' => 5,
                'total_rooms' => 6,
                'available_rooms' => 6,
                'amenities' => [
                    '2 AC Central',
                    'WiFi Gratis 150 Mbps',
                    '2 TV LED (43" + 32")',
                    '2 Kamar Mandi',
                    'Full Kitchen',
                    'Dining Room',
                    'Living Room Luas',
                    'Private Terrace',
                    'Garden View',
                    'Outdoor Furniture',
                    'BBQ Area Premium',
                    'Washing Machine',
                    'Traditional Furniture',
                    'Nature Sounds'
                ],
                'images' => [
                    '/images/cottagee1.jpeg',
                    '/images/cottagee2.jpeg',
                    '/images/cottagee3.jpeg',
                    '/images/cottagee4.jpeg'
                ],
                'is_active' => true
            ]
        ];

        foreach ($roomTypes as $roomType) {
            RoomType::create($roomType);
        }
    }
}

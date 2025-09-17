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
                'name' => 'Superior',
                'description' => 'Kamar Superior dengan pemandangan taman yang indah. Dilengkapi dengan fasilitas modern dan nyaman untuk menginap yang menyenangkan.',
                'price_per_night' => 450000,
                'max_occupancy' => 2,
                'total_rooms' => 10,
                'amenities' => [
                    'AC',
                    'TV LED 32"',
                    'Wi-Fi Gratis',
                    'Kamar Mandi Dalam',
                    'Air Panas',
                    'Lemari Es Mini',
                    'Teras Pribadi',
                    'Pemandangan Taman'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Deluxe',
                'description' => 'Kamar Deluxe yang lebih luas dengan balkon pribadi dan pemandangan gunung. Fasilitas premium untuk kenyamanan maksimal.',
                'price_per_night' => 650000,
                'max_occupancy' => 3,
                'total_rooms' => 8,
                'amenities' => [
                    'AC',
                    'TV LED 43"',
                    'Wi-Fi Gratis',
                    'Kamar Mandi Dalam',
                    'Air Panas',
                    'Lemari Es Mini',
                    'Balkon Pribadi',
                    'Pemandangan Gunung',
                    'Sofa',
                    'Meja Kerja'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Suite',
                'description' => 'Suite mewah dengan ruang tamu terpisah dan pemandangan spektakuler. Pilihan terbaik untuk pengalaman menginap yang tak terlupakan.',
                'price_per_night' => 950000,
                'max_occupancy' => 4,
                'total_rooms' => 4,
                'amenities' => [
                    'AC',
                    'TV LED 55"',
                    'Wi-Fi Gratis',
                    'Kamar Mandi Dalam',
                    'Air Panas',
                    'Lemari Es Mini',
                    'Ruang Tamu Terpisah',
                    'Balkon Luas',
                    'Pemandangan Premium',
                    'Sofa Set',
                    'Meja Makan',
                    'Kitchenette'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Family',
                'description' => 'Kamar keluarga yang luas dengan 2 tempat tidur dan area bermain anak. Cocok untuk liburan keluarga yang menyenangkan.',
                'price_per_night' => 750000,
                'max_occupancy' => 6,
                'total_rooms' => 6,
                'amenities' => [
                    'AC',
                    'TV LED 43"',
                    'Wi-Fi Gratis',
                    'Kamar Mandi Dalam',
                    'Air Panas',
                    '2 Tempat Tidur',
                    'Area Bermain Anak',
                    'Lemari Es',
                    'Teras Keluarga',
                    'Sofa Bed'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
                ],
                'is_active' => true
            ]
        ];

        foreach ($roomTypes as $roomType) {
            RoomType::create($roomType);
        }
    }
}

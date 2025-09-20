<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HotelPhoto;
use App\Models\RoomType;

class HotelPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing room types
        $roomTypes = RoomType::all();
        
        // Sample hotel photos data
        $hotelPhotos = [
            // Featured photos
            [
                'title' => 'Hotel Selecta Exterior View',
                'description' => 'Beautiful exterior view of Hotel Selecta with mountain backdrop',
                'image_path' => 'hotel_photos/hotel_exterior_1.jpg',
                'category' => 'exterior',
                'room_type_id' => null,
                'sort_order' => 1,
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'title' => 'Hotel Lobby',
                'description' => 'Elegant and spacious hotel lobby with modern design',
                'image_path' => 'hotel_photos/hotel_lobby_1.jpg',
                'category' => 'interior',
                'room_type_id' => null,
                'sort_order' => 2,
                'is_featured' => true,
                'is_active' => true
            ],
            [
                'title' => 'Swimming Pool Area',
                'description' => 'Refreshing swimming pool with mountain view',
                'image_path' => 'hotel_photos/hotel_pool_1.jpg',
                'category' => 'facility',
                'room_type_id' => null,
                'sort_order' => 3,
                'is_featured' => true,
                'is_active' => true
            ],
            
            // Exterior photos
            [
                'title' => 'Hotel Front View',
                'description' => 'Front entrance of Hotel Selecta',
                'image_path' => 'hotel_photos/hotel_front_1.jpg',
                'category' => 'exterior',
                'room_type_id' => null,
                'sort_order' => 4,
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'title' => 'Garden Area',
                'description' => 'Beautiful garden surrounding the hotel',
                'image_path' => 'hotel_photos/hotel_garden_1.jpg',
                'category' => 'exterior',
                'room_type_id' => null,
                'sort_order' => 5,
                'is_featured' => false,
                'is_active' => true
            ],
            
            // Interior photos
            [
                'title' => 'Restaurant Area',
                'description' => 'Cozy restaurant with local and international cuisine',
                'image_path' => 'hotel_photos/hotel_restaurant_1.jpg',
                'category' => 'interior',
                'room_type_id' => null,
                'sort_order' => 6,
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'title' => 'Reception Desk',
                'description' => 'Modern reception desk with friendly staff',
                'image_path' => 'hotel_photos/hotel_reception_1.jpg',
                'category' => 'interior',
                'room_type_id' => null,
                'sort_order' => 7,
                'is_featured' => false,
                'is_active' => true
            ],
            
            // Facility photos
            [
                'title' => 'Fitness Center',
                'description' => 'Well-equipped fitness center for guests',
                'image_path' => 'hotel_photos/hotel_gym_1.jpg',
                'category' => 'facility',
                'room_type_id' => null,
                'sort_order' => 8,
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'title' => 'Conference Room',
                'description' => 'Modern conference room for business meetings',
                'image_path' => 'hotel_photos/hotel_conference_1.jpg',
                'category' => 'facility',
                'room_type_id' => null,
                'sort_order' => 9,
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'title' => 'Spa Area',
                'description' => 'Relaxing spa area for ultimate comfort',
                'image_path' => 'hotel_photos/hotel_spa_1.jpg',
                'category' => 'facility',
                'room_type_id' => null,
                'sort_order' => 10,
                'is_featured' => false,
                'is_active' => true
            ],
            
            // General photos
            [
                'title' => 'Hotel Corridor',
                'description' => 'Clean and well-lit hotel corridor',
                'image_path' => 'hotel_photos/hotel_corridor_1.jpg',
                'category' => 'general',
                'room_type_id' => null,
                'sort_order' => 11,
                'is_featured' => false,
                'is_active' => true
            ],
            [
                'title' => 'Parking Area',
                'description' => 'Spacious parking area for guests',
                'image_path' => 'hotel_photos/hotel_parking_1.jpg',
                'category' => 'general',
                'room_type_id' => null,
                'sort_order' => 12,
                'is_featured' => false,
                'is_active' => true
            ]
        ];
        
        // Add room-specific photos if room types exist
        if ($roomTypes->count() > 0) {
            foreach ($roomTypes as $index => $roomType) {
                $hotelPhotos[] = [
                    'title' => $roomType->name . ' - Bedroom',
                    'description' => 'Comfortable ' . $roomType->name . ' bedroom with modern amenities',
                    'image_path' => 'hotel_photos/room_' . strtolower(str_replace(' ', '_', $roomType->name)) . '_bedroom.jpg',
                    'category' => 'room',
                    'room_type_id' => $roomType->id,
                    'sort_order' => 20 + ($index * 3),
                    'is_featured' => $index === 0, // Make first room type featured
                    'is_active' => true
                ];
                
                $hotelPhotos[] = [
                    'title' => $roomType->name . ' - Bathroom',
                    'description' => 'Clean and modern bathroom in ' . $roomType->name,
                    'image_path' => 'hotel_photos/room_' . strtolower(str_replace(' ', '_', $roomType->name)) . '_bathroom.jpg',
                    'category' => 'room',
                    'room_type_id' => $roomType->id,
                    'sort_order' => 21 + ($index * 3),
                    'is_featured' => false,
                    'is_active' => true
                ];
                
                $hotelPhotos[] = [
                    'title' => $roomType->name . ' - View',
                    'description' => 'Beautiful view from ' . $roomType->name . ' window',
                    'image_path' => 'hotel_photos/room_' . strtolower(str_replace(' ', '_', $roomType->name)) . '_view.jpg',
                    'category' => 'room',
                    'room_type_id' => $roomType->id,
                    'sort_order' => 22 + ($index * 3),
                    'is_featured' => false,
                    'is_active' => true
                ];
            }
        }
        
        // Insert photos into database
        foreach ($hotelPhotos as $photo) {
            HotelPhoto::create($photo);
        }
        
        $this->command->info('Hotel photos seeded successfully!');
        $this->command->info('Total photos created: ' . count($hotelPhotos));
    }
}

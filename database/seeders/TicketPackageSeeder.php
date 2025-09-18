<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Package;

class TicketPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing packages safely
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Package::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create Tiket Reguler
        Package::create([
            'name' => 'Tiket Reguler',
            'description' => 'Termasuk: Kolam renang, waterpark, taman bunga, dan fasilitas dasar lainnya',
            'price' => 50000,
            'features' => [
                'Tiket masuk',
                'Akses kolam renang',
                'Waterpark',
                'Kolam Ikan',
                'Akuarium',
                'Taman Bunga',
                'Dino Ranch',
                'Asuransi kecelakaan'
            ],
            'badge' => '',
            'is_active' => true
        ]);

        // Create Tiket Terusan
        Package::create([
            'name' => 'Tiket Terusan',
            'description' => 'Akses ke semua wahana dan fasilitas Taman Rekreasi Selecta termasuk tiket masuk',
            'price' => 80000,
            'features' => [
                '1x Tiket Masuk ke Taman Rekreasi Selecta untuk 1 Pengunjung',
                '1x Tiket Masuk ke Dino Ranch untuk 1 Pengunjung',
                '1x Tiket Masuk ke Bioskop 4D untuk 1 Pengunjung',
                '1x Tiket Masuk ke Mobil Ayun untuk 1 Pengunjung',
                '1x Tiket Masuk ke Mini Bumper Car untuk 1 Pengunjung',
                '1x Tiket Masuk ke Paddle Boat untuk 1 Pengunjung',
                '1x Akses ke Bianglala untuk 1 Pengunjung',
                '1x Akses ke Dino Ride untuk 1 Pengunjung',
                '1x Akses ke Sky Bike untuk 1 Pengunjung',
                '1x Akses ke Garden Tram untuk 1 Pengunjung',
                '1x Akses ke Kolam Renang untuk 1 Pengunjung',
                '1x Akses ke Waterpark untuk 1 Pengunjung',
                '1x Akses ke Kolam Ikan untuk 1 Pengunjung',
                '1x Akses ke Taman Lumut untuk 1 Pengunjung',
                '1x Akses ke Taman Bunga untuk 1 Pengunjung',
                '1x Akses ke Tagada Disco untuk 1 Pengunjung'
            ],
            'badge' => 'Premium',
            'is_active' => true
        ]);
    }
}

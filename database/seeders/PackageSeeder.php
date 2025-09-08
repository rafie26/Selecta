<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;

class PackageSeeder extends Seeder
{
    public function run()
    {
        Package::create([
            'name' => 'Reguler Pass',
            'description' => 'Paket tiket masuk reguler untuk menikmati semua wahana dan fasilitas',
            'price' => 50000,
            'features' => [
                'Akses ke semua wahana',
                'Fasilitas parkir',
                'Area bermain anak',
                'Spot foto menarik'
            ],
            'badge' => null,
            'is_active' => true
        ]);

        Package::create([
            'name' => 'Premium Package',
            'description' => 'Paket premium dengan fasilitas tambahan dan prioritas akses',
            'price' => 75000,
            'features' => [
                'Akses ke semua wahana',
                'Fasilitas parkir VIP',
                'Area bermain anak',
                'Spot foto menarik',
                'Prioritas akses wahana',
                'Welcome drink',
                'Souvenir eksklusif'
            ],
            'badge' => 'Popular',
            'is_active' => true
        ]);

        Package::create([
            'name' => 'Family Bundle',
            'description' => 'Paket khusus untuk keluarga dengan harga spesial',
            'price' => 180000,
            'features' => [
                'Akses untuk 4 orang',
                'Fasilitas parkir',
                'Area bermain anak',
                'Spot foto menarik',
                'Makan siang keluarga',
                'Foto keluarga gratis'
            ],
            'badge' => 'Best Value',
            'is_active' => true
        ]);
    }
}

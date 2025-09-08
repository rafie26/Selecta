<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;

class TicketController extends Controller
{
    public function index()
    {
        try {
            $packages = Package::active()->get();
            
            // If no packages found, create default ones
            if ($packages->isEmpty()) {
                $this->createDefaultPackages();
                $packages = Package::active()->get();
            }
            
            return view('ticket.index', compact('packages'));
        } catch (\Exception $e) {
            // If table doesn't exist, return empty collection
            $packages = collect([]);
            return view('ticket.index', compact('packages'));
        }
    }
    
    private function createDefaultPackages()
    {
        Package::create([
            'name' => 'Paket Reguler',
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
            'name' => 'Paket Premium',
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
            'name' => 'Paket Family',
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
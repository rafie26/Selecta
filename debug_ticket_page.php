<?php
require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Package;

echo "=== Debug Ticket Page Issues ===\n\n";

try {
    echo "1. Testing Package Model:\n";
    $packages = Package::active()->get();
    echo "   - Active packages count: " . $packages->count() . "\n";
    
    if ($packages->isEmpty()) {
        echo "   - No packages found, creating default packages...\n";
        
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
        
        $packages = Package::active()->get();
        echo "   - Created " . $packages->count() . " default packages\n";
    }
    
    echo "\n2. Package Details:\n";
    foreach ($packages as $package) {
        echo "   - ID: {$package->id}, Name: {$package->name}, Price: Rp " . number_format($package->price, 0, ',', '.') . "\n";
        echo "     Features: " . (is_array($package->features) ? implode(', ', $package->features) : 'None') . "\n";
        echo "     Badge: " . ($package->badge ?: 'None') . "\n";
        echo "     Active: " . ($package->is_active ? 'Yes' : 'No') . "\n\n";
    }
    
    echo "3. Testing JSON encoding for JavaScript:\n";
    $priceData = $packages->pluck('price', 'id');
    $nameData = $packages->pluck('name', 'id');
    
    echo "   - Price data: " . json_encode($priceData) . "\n";
    echo "   - Name data: " . json_encode($nameData) . "\n";
    
    echo "\n4. Testing TicketController simulation:\n";
    $controllerPackages = Package::active()->get();
    if ($controllerPackages->isEmpty()) {
        echo "   - ERROR: No packages available for controller\n";
    } else {
        echo "   - SUCCESS: Controller would receive " . $controllerPackages->count() . " packages\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Debug Complete ===\n";

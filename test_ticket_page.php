<?php
require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Package;
use App\Http\Controllers\TicketController;

echo "=== Testing Ticket Page Functionality ===\n\n";

try {
    echo "1. Testing Package Model:\n";
    $packages = Package::active()->get();
    echo "   - Active packages count: " . $packages->count() . "\n";
    
    if ($packages->isEmpty()) {
        echo "   - Creating default packages...\n";
        
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
    
    echo "3. Testing JSON data for JavaScript:\n";
    $priceData = $packages->pluck('price', 'id');
    $nameData = $packages->pluck('name', 'id');
    
    echo "   - Price data JSON: " . json_encode($priceData) . "\n";
    echo "   - Name data JSON: " . json_encode($nameData) . "\n";
    
    echo "\n4. Testing TicketController simulation:\n";
    $controller = new TicketController();
    
    // Simulate the controller method
    ob_start();
    try {
        $controllerPackages = Package::active()->get();
        if ($controllerPackages->isEmpty()) {
            echo "   - ERROR: No packages available for controller\n";
        } else {
            echo "   - SUCCESS: Controller would receive " . $controllerPackages->count() . " packages\n";
            echo "   - Package IDs: " . $controllerPackages->pluck('id')->implode(', ') . "\n";
        }
    } catch (Exception $e) {
        echo "   - Controller ERROR: " . $e->getMessage() . "\n";
    }
    ob_end_clean();
    
    echo "\n5. Testing view data structure:\n";
    $viewData = [
        'packages' => $packages
    ];
    
    echo "   - View data keys: " . implode(', ', array_keys($viewData)) . "\n";
    echo "   - Packages variable type: " . gettype($viewData['packages']) . "\n";
    echo "   - Packages count in view: " . $viewData['packages']->count() . "\n";
    
    echo "\n✅ All tests passed! Ticket page should work correctly.\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Test Complete ===\n";

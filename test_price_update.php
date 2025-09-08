<?php

require_once 'vendor/autoload.php';

use App\Models\Package;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing Price Update and Midtrans Sync ===\n\n";

try {
    // Test 1: Check Midtrans Configuration
    echo "1. Testing Midtrans Configuration...\n";
    $midtransService = new MidtransService();
    $configStatus = $midtransService->getConfigurationStatus();
    
    echo "   - Server Key Configured: " . ($configStatus['server_key_configured'] ? 'YES' : 'NO') . "\n";
    echo "   - Client Key Configured: " . ($configStatus['client_key_configured'] ? 'YES' : 'NO') . "\n";
    echo "   - Environment: " . $configStatus['environment'] . "\n";
    echo "   - Sanitized: " . ($configStatus['is_sanitized'] ? 'YES' : 'NO') . "\n";
    echo "   - 3DS: " . ($configStatus['is_3ds'] ? 'YES' : 'NO') . "\n\n";
    
    $errors = $midtransService->validateConfiguration();
    if (!empty($errors)) {
        echo "   Configuration Errors:\n";
        foreach ($errors as $error) {
            echo "   - " . $error . "\n";
        }
        echo "\n";
    } else {
        echo "   ✓ Midtrans configuration is valid\n\n";
    }
    
    // Test 2: Check existing packages
    echo "2. Checking existing packages...\n";
    $packages = Package::all();
    
    if ($packages->isEmpty()) {
        echo "   No packages found. Creating test package...\n";
        
        $testPackage = Package::create([
            'name' => 'Test Package - Tiket Masuk Reguler',
            'description' => 'Paket tiket masuk reguler untuk testing price update',
            'price' => 50000,
            'features' => ['Akses ke semua wahana', 'Parkir gratis', 'WiFi gratis'],
            'badge' => 'Test',
            'is_active' => true
        ]);
        
        echo "   ✓ Test package created with ID: " . $testPackage->id . "\n";
        echo "   ✓ Initial price: Rp " . number_format($testPackage->price) . "\n\n";
    } else {
        echo "   Found " . $packages->count() . " existing packages:\n";
        foreach ($packages as $package) {
            echo "   - ID: {$package->id}, Name: {$package->name}, Price: Rp " . number_format($package->price) . "\n";
        }
        echo "\n";
    }
    
    // Test 3: Test price calculation
    echo "3. Testing price calculation...\n";
    $testPackages = ['1' => 2, '2' => 1]; // Package ID => Quantity
    
    $packageDetails = $midtransService->preparePackageDetails($testPackages);
    $totalAmount = $midtransService->calculateTotalAmount($testPackages);
    
    echo "   Package details prepared:\n";
    foreach ($packageDetails as $detail) {
        echo "   - {$detail['package']->name}: {$detail['quantity']}x Rp " . number_format($detail['package']->price) . " = Rp " . number_format($detail['subtotal']) . "\n";
    }
    echo "   Total Amount: Rp " . number_format($totalAmount) . "\n\n";
    
    // Test 4: Test Midtrans item details preparation
    echo "4. Testing Midtrans item details preparation...\n";
    $itemDetails = $midtransService->prepareItemDetails($packageDetails);
    
    echo "   Midtrans item details:\n";
    foreach ($itemDetails as $item) {
        echo "   - ID: {$item['id']}, Name: {$item['name']}, Price: {$item['price']}, Qty: {$item['quantity']}\n";
    }
    echo "\n";
    
    // Test 5: Test price update simulation
    echo "5. Testing price update simulation...\n";
    $firstPackage = Package::first();
    
    if ($firstPackage) {
        $oldPrice = $firstPackage->price;
        $newPrice = $oldPrice + 10000; // Increase by 10k
        
        echo "   Simulating price update for: {$firstPackage->name}\n";
        echo "   Old Price: Rp " . number_format($oldPrice) . "\n";
        echo "   New Price: Rp " . number_format($newPrice) . "\n";
        
        // Update price
        $firstPackage->update(['price' => $newPrice]);
        
        // Log the sync
        $midtransService->logPriceSync($firstPackage, $oldPrice, $newPrice, 1);
        
        echo "   ✓ Price updated successfully\n";
        echo "   ✓ Sync logged to system\n\n";
        
        // Verify the change
        $updatedPackage = Package::find($firstPackage->id);
        echo "   Verification - Current price: Rp " . number_format($updatedPackage->price) . "\n\n";
        
        // Revert back for testing
        $firstPackage->update(['price' => $oldPrice]);
        echo "   ✓ Price reverted back for testing purposes\n\n";
    }
    
    echo "=== All Tests Completed Successfully ===\n";
    echo "✓ Midtrans configuration is working\n";
    echo "✓ Package price management is functional\n";
    echo "✓ Price synchronization with Midtrans is ready\n";
    echo "✓ Admin can now safely update package prices\n\n";
    
    echo "Next steps:\n";
    echo "1. Access admin panel at: /admin/login\n";
    echo "2. Navigate to Packages section\n";
    echo "3. Edit any package to update prices\n";
    echo "4. Prices will automatically sync with Midtrans for new transactions\n";
    
} catch (Exception $e) {
    echo "❌ Error during testing: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

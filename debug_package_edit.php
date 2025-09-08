<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Package;
use Illuminate\Support\Facades\DB;

echo "=== Debug Package Edit Issue ===\n\n";

try {
    // Check if packages exist
    $packages = Package::all();
    echo "Total packages in database: " . $packages->count() . "\n\n";
    
    if ($packages->count() > 0) {
        echo "Existing packages:\n";
        foreach ($packages as $package) {
            echo "ID: {$package->id} | Name: {$package->name} | Price: Rp " . number_format($package->price) . " | Updated: {$package->updated_at}\n";
        }
        echo "\n";
        
        // Test update on first package
        $firstPackage = $packages->first();
        $originalPrice = $firstPackage->price;
        $newPrice = $originalPrice + 5000;
        
        echo "Testing update on package ID: {$firstPackage->id}\n";
        echo "Original price: Rp " . number_format($originalPrice) . "\n";
        echo "New price: Rp " . number_format($newPrice) . "\n";
        
        // Perform update
        $updated = $firstPackage->update(['price' => $newPrice]);
        
        if ($updated) {
            echo "✓ Update method returned: " . ($updated ? 'true' : 'false') . "\n";
            
            // Refresh and check
            $firstPackage->refresh();
            echo "✓ Price after refresh: Rp " . number_format($firstPackage->price) . "\n";
            
            // Check in database directly
            $dbPackage = DB::table('packages')->where('id', $firstPackage->id)->first();
            echo "✓ Price from database: Rp " . number_format($dbPackage->price) . "\n";
            
            if ($firstPackage->price == $newPrice) {
                echo "✅ UPDATE SUCCESSFUL - Price changed correctly\n";
            } else {
                echo "❌ UPDATE FAILED - Price not changed\n";
            }
            
            // Revert back
            $firstPackage->update(['price' => $originalPrice]);
            echo "✓ Reverted price back to original\n";
        } else {
            echo "❌ Update method failed\n";
        }
    } else {
        echo "No packages found. Creating test package...\n";
        $testPackage = Package::create([
            'name' => 'Test Package',
            'description' => 'Test package for debugging',
            'price' => 25000,
            'features' => ['Test feature'],
            'is_active' => true
        ]);
        echo "✓ Test package created with ID: {$testPackage->id}\n";
    }
    
    // Check database connection
    echo "\nDatabase connection test:\n";
    $connection = DB::connection();
    echo "✓ Database connected: " . $connection->getDatabaseName() . "\n";
    
    // Check packages table structure
    echo "\nPackages table structure:\n";
    $columns = DB::select("DESCRIBE packages");
    foreach ($columns as $column) {
        echo "- {$column->Field}: {$column->Type}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}

echo "\n=== Debug Complete ===\n";

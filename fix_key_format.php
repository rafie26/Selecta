<?php

echo "=== FIXING MIDTRANS KEY FORMAT ===\n";

// Read current .env file
$envFile = '.env';
$envContent = file_get_contents($envFile);

echo "Current keys detected:\n";
$lines = explode("\n", $envContent);
foreach ($lines as $line) {
    if (strpos($line, 'MIDTRANS') !== false) {
        echo "  " . $line . "\n";
    }
}

// Fix the key formats - add SB- prefix if missing
$corrections = [
    // If key starts with Mid-server-, replace with SB-Mid-server-
    '/MIDTRANS_SERVER_KEY=Mid-server-(.*)/' => 'MIDTRANS_SERVER_KEY=SB-Mid-server-$1',
    // If key starts with Mid-client-, replace with SB-Mid-client-  
    '/MIDTRANS_CLIENT_KEY=Mid-client-(.*)/' => 'MIDTRANS_CLIENT_KEY=SB-Mid-client-$1'
];

$updated = false;
foreach ($corrections as $pattern => $replacement) {
    if (preg_match($pattern, $envContent)) {
        $envContent = preg_replace($pattern, $replacement, $envContent);
        $updated = true;
        echo "\n✅ Fixed key format: " . $replacement . "\n";
    }
}

if ($updated) {
    file_put_contents($envFile, $envContent);
    echo "\n🎉 .env file updated with correct sandbox key formats!\n";
    
    // Show corrected keys
    echo "\nCorrected keys:\n";
    $lines = explode("\n", $envContent);
    foreach ($lines as $line) {
        if (strpos($line, 'MIDTRANS') !== false) {
            echo "  " . $line . "\n";
        }
    }
} else {
    echo "\nNo format corrections needed.\n";
}

// Test the corrected keys
echo "\n=== TESTING CORRECTED KEYS ===\n";

require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$serverKey = $_ENV['MIDTRANS_SERVER_KEY'] ?? null;
$clientKey = $_ENV['MIDTRANS_CLIENT_KEY'] ?? null;

echo "Server Key Format: " . (strpos($serverKey, 'SB-Mid-server-') === 0 ? "✅ CORRECT" : "❌ WRONG") . "\n";
echo "Client Key Format: " . (strpos($clientKey, 'SB-Mid-client-') === 0 ? "✅ CORRECT" : "❌ WRONG") . "\n";

// Test API with corrected keys
use Midtrans\Config;
use Midtrans\Snap;

Config::$serverKey = $serverKey;
Config::$isProduction = false;
Config::$isSanitized = true;
Config::$is3ds = true;

try {
    $params = [
        'transaction_details' => [
            'order_id' => 'TEST-CORRECTED-' . time(),
            'gross_amount' => 25000,
        ],
        'customer_details' => [
            'first_name' => 'Test',
            'last_name' => 'Corrected',
            'email' => 'test@corrected.com',
            'phone' => '08123456789',
        ],
    ];
    
    $snapToken = Snap::getSnapToken($params);
    echo "API Test: ✅ SUCCESS with corrected format!\n";
    echo "Token: " . substr($snapToken, 0, 30) . "...\n";
    
} catch (Exception $e) {
    echo "API Test: ❌ FAILED - " . $e->getMessage() . "\n";
}

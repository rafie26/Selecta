<?php

// Simple PHP script to test and fix Midtrans configuration
echo "=== Midtrans Configuration Fix ===\n";

// Read .env file directly
$envFile = '.env';
if (!file_exists($envFile)) {
    echo "ERROR: .env file not found!\n";
    exit(1);
}

$envContent = file_get_contents($envFile);
echo "Current .env content (Midtrans section):\n";

// Extract Midtrans lines
$lines = explode("\n", $envContent);
foreach ($lines as $line) {
    if (strpos($line, 'MIDTRANS') !== false) {
        echo $line . "\n";
    }
}

// Correct credentials from memory
$correctCredentials = [
    'MIDTRANS_SERVER_KEY' => 'SB-Mid-server-GwS6LjPnpotNiagCOBXBzqNB',
    'MIDTRANS_CLIENT_KEY' => 'SB-Mid-client-nKsqvar5cn60u2Lv',
    'MIDTRANS_IS_PRODUCTION' => 'false'
];

echo "\n=== Fixing Credentials ===\n";

$updated = false;
foreach ($correctCredentials as $key => $value) {
    $pattern = '/^' . preg_quote($key) . '=.*$/m';
    if (preg_match($pattern, $envContent)) {
        $envContent = preg_replace($pattern, $key . '=' . $value, $envContent);
        echo "Updated: $key\n";
    } else {
        $envContent .= "\n$key=$value";
        echo "Added: $key\n";
    }
    $updated = true;
}

if ($updated) {
    file_put_contents($envFile, $envContent);
    echo "\n✅ .env file updated successfully!\n";
} else {
    echo "\nNo changes needed.\n";
}

// Test the configuration
echo "\n=== Testing Configuration ===\n";

require_once 'vendor/autoload.php';

// Load the updated .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Midtrans\Config;
use Midtrans\Snap;

Config::$serverKey = $_ENV['MIDTRANS_SERVER_KEY'];
Config::$isProduction = $_ENV['MIDTRANS_IS_PRODUCTION'] === 'true';
Config::$isSanitized = true;
Config::$is3ds = true;

echo "Server Key: " . substr(Config::$serverKey, 0, 20) . "...\n";
echo "Is Production: " . (Config::$isProduction ? 'true' : 'false') . "\n";

// Test API call
$params = [
    'transaction_details' => [
        'order_id' => 'TEST-FIX-' . time(),
        'gross_amount' => 10000,
    ],
    'customer_details' => [
        'first_name' => 'Test',
        'last_name' => 'Fix',
        'email' => 'test@fix.com',
        'phone' => '08111222333',
    ],
];

try {
    $snapToken = Snap::getSnapToken($params);
    echo "\n✅ SUCCESS: Midtrans API working!\n";
    echo "Snap Token: " . substr($snapToken, 0, 30) . "...\n";
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    if (strpos($e->getMessage(), '401') !== false) {
        echo "This is still a 401 error. The server key might be invalid.\n";
        echo "Please verify the server key with Midtrans dashboard.\n";
    }
}

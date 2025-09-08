<?php
/**
 * Debug script untuk Midtrans Production API Keys
 */

require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "🔍 Debugging Midtrans Production Configuration\n";
echo "============================================\n\n";

// Check environment variables
$merchantId = $_ENV['MIDTRANS_MERCHANT_ID'] ?? 'NOT_SET';
$serverKey = $_ENV['MIDTRANS_SERVER_KEY'] ?? 'NOT_SET';
$clientKey = $_ENV['MIDTRANS_CLIENT_KEY'] ?? 'NOT_SET';
$isProduction = $_ENV['MIDTRANS_IS_PRODUCTION'] ?? 'false';

echo "📋 Environment Variables:\n";
echo "MIDTRANS_MERCHANT_ID: {$merchantId}\n";
echo "MIDTRANS_SERVER_KEY: " . substr($serverKey, 0, 15) . "...\n";
echo "MIDTRANS_CLIENT_KEY: " . substr($clientKey, 0, 15) . "...\n";
echo "MIDTRANS_IS_PRODUCTION: {$isProduction}\n\n";

// Check key formats
echo "🔑 Key Format Analysis:\n";

// Server Key Analysis
if (strpos($serverKey, 'Mid-server-') === 0) {
    echo "✅ Server Key: Correct production format\n";
} elseif (strpos($serverKey, 'SB-Mid-server-') === 0) {
    echo "❌ Server Key: This is sandbox format, not production!\n";
} else {
    echo "❌ Server Key: Invalid format\n";
}

// Client Key Analysis
if (strpos($clientKey, 'Mid-client-') === 0) {
    echo "✅ Client Key: Correct production format\n";
} elseif (strpos($clientKey, 'SB-Mid-client-') === 0) {
    echo "❌ Client Key: This is sandbox format, not production!\n";
} else {
    echo "❌ Client Key: Invalid format\n";
}

echo "\n";

// Test API Connection
echo "🌐 Testing API Connection:\n";

try {
    // Set Midtrans config
    \Midtrans\Config::$serverKey = $serverKey;
    \Midtrans\Config::$isProduction = ($isProduction === 'true');
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    // Test transaction
    $params = [
        'transaction_details' => [
            'order_id' => 'TEST-' . time(),
            'gross_amount' => 1000,
        ],
        'customer_details' => [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone' => '08123456789',
        ],
    ];

    echo "Attempting to create Snap token...\n";
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    echo "✅ SUCCESS: Snap token created successfully!\n";
    echo "Token: " . substr($snapToken, 0, 20) . "...\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    
    if (strpos($e->getMessage(), '401') !== false) {
        echo "\n🔧 Possible Solutions:\n";
        echo "1. Verify API keys are correct in Midtrans dashboard\n";
        echo "2. Check if merchant account is activated for production\n";
        echo "3. Ensure keys are not mixed (production vs sandbox)\n";
        echo "4. Contact Midtrans support if account needs activation\n";
    }
}

echo "\n";

// Check Laravel config
echo "🔧 Laravel Configuration Check:\n";
if (file_exists(__DIR__ . '/config/midtrans.php')) {
    $config = include __DIR__ . '/config/midtrans.php';
    echo "Config file exists: ✅\n";
    echo "Production mode in config: " . ($config['is_production'] ? 'true' : 'false') . "\n";
} else {
    echo "❌ Config file missing!\n";
}

echo "\n";
echo "📝 Recommendations:\n";
echo "1. Double-check API keys in Midtrans dashboard\n";
echo "2. Ensure merchant account is approved for production\n";
echo "3. Run: php artisan config:clear\n";
echo "4. Verify .env file has correct keys\n";

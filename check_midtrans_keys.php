<?php
/**
 * Simple check for Midtrans keys configuration
 */

echo "🔍 Checking Midtrans Configuration\n";
echo "=================================\n\n";

// Check if .env file exists
$envFile = __DIR__ . '/.env';
if (!file_exists($envFile)) {
    echo "❌ .env file not found!\n";
    echo "Please copy .env.example to .env and update the keys.\n";
    exit(1);
}

// Read .env file
$envContent = file_get_contents($envFile);
$envLines = explode("\n", $envContent);

$keys = [];
foreach ($envLines as $line) {
    if (strpos($line, 'MIDTRANS_') === 0) {
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $keys[trim($parts[0])] = trim($parts[1]);
        }
    }
}

echo "📋 Found Midtrans Keys in .env:\n";
foreach ($keys as $key => $value) {
    if (strpos($key, 'KEY') !== false) {
        echo "{$key}: " . substr($value, 0, 15) . "...\n";
    } else {
        echo "{$key}: {$value}\n";
    }
}

echo "\n🔑 Key Format Analysis:\n";

// Check server key
$serverKey = $keys['MIDTRANS_SERVER_KEY'] ?? '';
if (empty($serverKey)) {
    echo "❌ MIDTRANS_SERVER_KEY is empty\n";
} elseif (strpos($serverKey, 'Mid-server-') === 0) {
    echo "✅ Server Key: Production format detected\n";
} elseif (strpos($serverKey, 'SB-Mid-server-') === 0) {
    echo "⚠️  Server Key: Sandbox format (should be production)\n";
} else {
    echo "❌ Server Key: Invalid format\n";
}

// Check client key
$clientKey = $keys['MIDTRANS_CLIENT_KEY'] ?? '';
if (empty($clientKey)) {
    echo "❌ MIDTRANS_CLIENT_KEY is empty\n";
} elseif (strpos($clientKey, 'Mid-client-') === 0) {
    echo "✅ Client Key: Production format detected\n";
} elseif (strpos($clientKey, 'SB-Mid-client-') === 0) {
    echo "⚠️  Client Key: Sandbox format (should be production)\n";
} else {
    echo "❌ Client Key: Invalid format\n";
}

// Check production mode
$isProduction = $keys['MIDTRANS_IS_PRODUCTION'] ?? 'false';
echo "Production Mode: " . ($isProduction === 'true' ? '✅ Enabled' : '❌ Disabled') . "\n";

echo "\n💡 Common Issues:\n";
echo "1. Keys might be from sandbox instead of production\n";
echo "2. Merchant account might not be activated for production\n";
echo "3. API keys might be incorrect or expired\n";
echo "4. Laravel config cache might need clearing\n";

echo "\n🔧 Next Steps:\n";
echo "1. Verify keys in Midtrans dashboard\n";
echo "2. Run: php artisan config:clear\n";
echo "3. Check if merchant is production-ready\n";

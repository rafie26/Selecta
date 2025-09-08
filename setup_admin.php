<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Check if admin user exists
    $adminUser = User::where('email', 'admin@selecta.com')->first();
    
    if ($adminUser) {
        echo "Admin user found: " . $adminUser->name . " (" . $adminUser->email . ")\n";
        echo "Current role: " . ($adminUser->role ?? 'NULL') . "\n";
        
        // Update role to admin if needed
        if ($adminUser->role !== 'admin') {
            $adminUser->update(['role' => 'admin']);
            echo "✅ Role updated to admin\n";
        } else {
            echo "✅ User already has admin role\n";
        }
    } else {
        // Create new admin user
        $adminUser = User::create([
            'name' => 'Admin Selecta',
            'email' => 'admin@selecta.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '+6281234567890',
            'phone_code' => '+62',
        ]);
        echo "✅ Admin user created successfully\n";
    }
    
    echo "\nAdmin credentials:\n";
    echo "Email: admin@selecta.com\n";
    echo "Password: admin123\n";
    echo "Role: admin\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    
    // If role column doesn't exist, we need to run the migration
    if (strpos($e->getMessage(), 'role') !== false) {
        echo "\n⚠️  The 'role' column might not exist. Please run: php artisan migrate\n";
    }
}

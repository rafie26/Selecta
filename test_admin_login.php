<?php

// Test admin login functionality
require_once 'vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Get admin user
    $admin = User::where('email', 'admin@selecta.com')->first();
    
    if ($admin) {
        echo "Admin user found:\n";
        echo "Name: " . $admin->name . "\n";
        echo "Email: " . $admin->email . "\n";
        echo "Role: " . ($admin->role ?? 'NULL') . "\n";
        echo "Password Hash: " . substr($admin->password, 0, 20) . "...\n";
        
        // Test password
        $passwordCheck = Hash::check('admin123', $admin->password);
        echo "Password 'admin123' valid: " . ($passwordCheck ? 'YES' : 'NO') . "\n";
        
        // Test role check
        $isAdmin = ($admin->role === 'admin');
        echo "Is admin role: " . ($isAdmin ? 'YES' : 'NO') . "\n";
        
        if ($passwordCheck && $isAdmin) {
            echo "\n✅ Admin login should work correctly!\n";
            echo "The issue might be in the login form or routes.\n";
        } else {
            echo "\n❌ There's an issue with admin credentials or role.\n";
        }
        
    } else {
        echo "❌ Admin user not found!\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

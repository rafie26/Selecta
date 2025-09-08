<?php

// Check admin user role in database
$host = 'localhost';
$dbname = 'selecta'; // Adjust if different
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check admin user
    $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE email = ?");
    $stmt->execute(['admin@selecta.com']);
    $adminUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($adminUser) {
        echo "Admin user found:\n";
        echo "ID: " . $adminUser['id'] . "\n";
        echo "Name: " . $adminUser['name'] . "\n";
        echo "Email: " . $adminUser['email'] . "\n";
        echo "Role: " . ($adminUser['role'] ?? 'NULL') . "\n";
        
        if ($adminUser['role'] !== 'admin') {
            echo "\n❌ Role is not 'admin' - fixing now...\n";
            $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE email = ?");
            $stmt->execute(['admin@selecta.com']);
            echo "✅ Role updated to 'admin'\n";
        } else {
            echo "\n✅ Role is correct: admin\n";
        }
    } else {
        echo "❌ Admin user not found!\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

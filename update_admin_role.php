<?php

// Direct SQL update to ensure admin user has correct role
$host = 'localhost';
$dbname = 'selecta';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // First, check if role column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'role'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE users ADD COLUMN role VARCHAR(255) DEFAULT 'user' AFTER google_id");
        echo "✅ Added role column\n";
    }
    
    // Update admin user role
    $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE email = 'admin@selecta.com'");
    $result = $stmt->execute();
    
    if ($result) {
        echo "✅ Admin role updated successfully\n";
        
        // Verify the update
        $stmt = $pdo->prepare("SELECT name, email, role FROM users WHERE email = 'admin@selecta.com'");
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "User: " . $user['name'] . "\n";
            echo "Email: " . $user['email'] . "\n";
            echo "Role: " . $user['role'] . "\n";
        }
    }
    
    echo "\n🎉 Admin user is now ready!\n";
    echo "Login with: admin@selecta.com / admin123\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

<?php

// Direct database connection to fix admin user
$host = 'localhost';
$dbname = 'selecta'; // Adjust database name if different
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if role column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'role'");
    if ($stmt->rowCount() == 0) {
        // Add role column if it doesn't exist
        $pdo->exec("ALTER TABLE users ADD COLUMN role VARCHAR(255) DEFAULT 'user' AFTER google_id");
        echo "✅ Role column added to users table\n";
    } else {
        echo "✅ Role column already exists\n";
    }
    
    // Check if admin user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute(['admin@selecta.com']);
    $adminUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($adminUser) {
        // Update existing user to admin role
        $stmt = $pdo->prepare("UPDATE users SET role = 'admin' WHERE email = ?");
        $stmt->execute(['admin@selecta.com']);
        echo "✅ Admin user role updated\n";
        echo "User: " . $adminUser['name'] . " (" . $adminUser['email'] . ")\n";
    } else {
        // Create new admin user
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, phone, phone_code, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
            'Admin Selecta',
            'admin@selecta.com',
            $hashedPassword,
            'admin',
            '+6281234567890',
            '+62'
        ]);
        echo "✅ Admin user created\n";
    }
    
    echo "\n🎉 Admin setup complete!\n";
    echo "Login credentials:\n";
    echo "Email: admin@selecta.com\n";
    echo "Password: admin123\n";
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    echo "\n💡 Please check your database configuration in .env file\n";
}

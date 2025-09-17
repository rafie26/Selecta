<?php

// Simple script to update available_rooms
$host = 'localhost';
$dbname = 'selecta';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Update available_rooms to match total_rooms
    $stmt = $pdo->prepare("UPDATE room_types SET available_rooms = total_rooms WHERE available_rooms IS NULL OR available_rooms = 0");
    $stmt->execute();
    
    echo "Updated available_rooms to match total_rooms.\n";
    
    // Show results
    $stmt = $pdo->query("SELECT id, name, total_rooms, available_rooms FROM room_types");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Room {$row['name']}: total={$row['total_rooms']}, available={$row['available_rooms']}\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

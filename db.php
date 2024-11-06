<?php
// Database configuration
$host = 'localhost';       // Server (localhost if you're using XAMPP or similar)
$db = 'pps_barangay_system'; // Database name
$user = 'root';            // Database username (default for XAMPP)
$pass = '';                // Database password (leave empty for default in XAMPP)

try {
    // Create a new connection using PDO
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    
    // Set PDO to throw exceptions on error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>

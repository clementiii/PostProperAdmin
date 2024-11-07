<?php
// Database configuration
$host = 'localhost';        // Server (localhost if you're using XAMPP or similar)
$db = 'pps_barangay_system'; // Database name
$user = 'root';             // Database username (default for XAMPP)
$pass = '';                 // Database password (leave empty for default in XAMPP)

try {
    // Create a new connection using PDO
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    
    // Set PDO to throw exceptions on error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optional: set the default fetch mode to fetch associative arrays
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // Error handling if the connection fails
    echo "Database connection failed: " . $e->getMessage();
    exit; // Exit if the database connection fails
}
?>

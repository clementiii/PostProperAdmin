<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php");
    exit;
}

// Check if an ID was provided
if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit;
}

$userId = $_GET['id'];

try {
    // Update user status to verified
    $sql = "UPDATE user_accounts SET status = 'verified' WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $userId);
    $stmt->execute();

    $_SESSION['success_message'] = "User verified successfully!";
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Error verifying user: " . $e->getMessage();
}

// Redirect back to users page
header("Location: users.php");
exit;
?>
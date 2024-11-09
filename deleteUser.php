<?php
session_start();
include 'db.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php"); // Redirect to the login page if not logged in
    exit;
}

// Check if an ID is provided and the user is allowed to delete
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Delete user from the user_accounts table
    try {
        $sql = "DELETE FROM user_accounts WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect back to the users page after deletion
        header("Location: users.php");
        exit;

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php");
    exit;
}

if (isset($_GET['id'])) {
    $adminId = (int) $_GET['id'];
    $currentAdminId = $_SESSION['admin_id'];
    
    try {
        // Delete the admin account from the database
        $sql = "DELETE FROM admin_accounts WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$adminId]);
        
        // If the deleted admin is the current user, log them out
        if ($adminId === $currentAdminId) {
            session_destroy(); // Destroy all session data
            header("Location: login.php?message=Your account has been deleted");
            exit;
        }
        
        // Otherwise, redirect back to the staff page with a success message
        header("Location: admin_staff.php?message=Admin deleted successfully.");
        exit;
    } catch (PDOException $e) {
        echo "Error deleting admin: " . $e->getMessage();
    }
}
?>
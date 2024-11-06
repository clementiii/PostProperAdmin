<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php");
    exit;
}

if (isset($_GET['id'])) {
    $adminId = (int) $_GET['id'];
    try {
        // Delete the admin account from the database
        $sql = "DELETE FROM admin_accounts WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$adminId]);
        
        // Redirect back to the staff page with a success message
        header("Location: admin_staff.php?message=Admin deleted successfully.");
        exit;
    } catch (PDOException $e) {
        echo "Error deleting admin: " . $e->getMessage();
    }
}
?>

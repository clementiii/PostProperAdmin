<?php
session_start();
include 'db.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check for session user ID
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User is not logged in"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $recipient = $_POST['recipient'];
    $message = $_POST['message'];

    // Validate message and recipient
    if (empty($recipient) || empty($message)) {
        echo json_encode(["status" => "error", "message" => "Recipient or message is empty"]);
        exit;
    }

    // Prepare and execute the statement
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, recipient_id, message, timestamp) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $user_id, $recipient, $message);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);  // Indicate message sent successfully
    } else {
        echo json_encode(["status" => "error", "message" => "Database insert failed"]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>

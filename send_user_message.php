<?php
header('Content-Type: application/json');
include 'db.php';

function logDebug($message, $data = null) {
    error_log($message . ($data ? ": " . print_r($data, true) : ""));
}

if (!isset($_POST['message']) || !isset($_POST['sender_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit;
}

$message = $_POST['message'];
$sender_id = $_POST['sender_id'];
$is_admin = 0; // Always 0 for user messages

try {
    // Insert message from user
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, message, is_admin) VALUES (:sender_id, :message, :is_admin)");
    $stmt->bindParam(':sender_id', $sender_id);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':is_admin', $is_admin);
    
    if ($stmt->execute()) {
        $lastId = $conn->lastInsertId();
        logDebug("User message inserted successfully", [
            'message_id' => $lastId,
            'sender_id' => $sender_id
        ]);
        
        echo json_encode(['status' => 'success']);
    } else {
        logDebug("Failed to insert message", $stmt->errorInfo());
        echo json_encode(['status' => 'error', 'message' => 'Failed to send message']);
    }
    
} catch (PDOException $e) {
    logDebug("Database error", $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
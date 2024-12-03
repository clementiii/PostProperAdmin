<?php
header('Content-Type: application/json');
include 'db.php';
session_start();

function logDebug($message, $data = null) {
    error_log($message . ($data ? ": " . print_r($data, true) : ""));
}

logDebug("Current session", $_SESSION);
logDebug("Admin ID from session", $_SESSION['admin_id'] ?? 'Not set');

if (!isset($_POST['message']) || !isset($_POST['recipient'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit;
}

$message = $_POST['message'];
$recipient = $_POST['recipient'];
$is_admin = isset($_POST['is_admin']) ? 1 : 0;
$admin_id = $_SESSION['admin_id'] ?? null;

logDebug("Message data", [
    'recipient' => $recipient,
    'is_admin' => $is_admin,
    'admin_id' => $admin_id
]);

try {
    // Get recipient's ID
    $stmt = $conn->prepare("SELECT id FROM user_accounts WHERE CONCAT(firstName, ' ', lastName) = :fullName");
    $stmt->bindParam(':fullName', $recipient);
    $stmt->execute();
    
    if ($stmt->rowCount() === 0) {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
        exit;
    }
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_id = $user['id'];
    
    // Insert message with admin_id
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, admin_id, message, is_admin) VALUES (:sender_id, :admin_id, :message, :is_admin)");
    $stmt->bindParam(':sender_id', $user_id);
    $stmt->bindParam(':admin_id', $admin_id);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':is_admin', $is_admin);
    
    if ($stmt->execute()) {
        $lastId = $conn->lastInsertId();
        logDebug("Message inserted successfully", [
            'message_id' => $lastId,
            'sender_id' => $user_id,
            'admin_id' => $admin_id,
            'is_admin' => $is_admin
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
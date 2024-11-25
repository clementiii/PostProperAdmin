<?php
header('Content-Type: application/json');
include 'db.php';

function logDebug($message, $data = null) {
    error_log($message . ($data ? ": " . print_r($data, true) : ""));
}

function findUserByFullName($conn, $fullName) {
    $fullName = trim(preg_replace('/\s+/', ' ', $fullName)); // Normalize spaces
    logDebug("Normalized full name", $fullName);

    try {
        $stmt = $conn->prepare("SELECT id FROM user_accounts WHERE CONCAT(firstName, ' ', lastName) = :fullName");
        $stmt->bindParam(':fullName', $fullName);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        logDebug("Exact match not found, trying LIKE query");

        $stmt = $conn->prepare("
            SELECT id, firstName, lastName,
                   CONCAT(firstName, ' ', lastName) as full_name
            FROM user_accounts 
            WHERE CONCAT(firstName, ' ', lastName) LIKE :fullNamePattern
        ");
        $pattern = '%' . $fullName . '%';
        $stmt->bindParam(':fullNamePattern', $pattern);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        logDebug("No user found for full name", $fullName);
        return null;
    } catch (PDOException $e) {
        logDebug("Database error in findUserByFullName", $e->getMessage());
        return null;
    }
}


if (!isset($_POST['recipient'])) {
    echo json_encode(['status' => 'error', 'message' => 'Recipient not specified']);
    exit;
}

$recipient = $_POST['recipient'];
logDebug("Loading chat for recipient", $recipient);

try {
    $user = findUserByFullName($conn, $recipient);
    
    if (!$user) {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
        exit;
    }

    $query = "SELECT m.*, 
              CONCAT(u.firstName, ' ', u.lastName) as sender_name,
              m.is_admin,
              m.timestamp
              FROM messages m
              LEFT JOIN user_accounts u ON m.sender_id = u.id
              WHERE m.sender_id = :user_id OR 
                    (m.is_admin = 1 AND m.sender_id = :user_id2)
              ORDER BY m.timestamp ASC";
              
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user['id']);
    $stmt->bindParam(':user_id2', $user['id']);
    $stmt->execute();
    
    $messages = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $messages[] = [
            'id' => $row['id'],
            'sender_id' => $row['sender_id'],
            'message' => $row['message'],
            'is_admin' => (bool)$row['is_admin'],
            'timestamp' => $row['timestamp'],
            'sender_name' => $row['sender_name']
        ];
    }
    
    logDebug("Found messages", count($messages));
    echo json_encode($messages);
    
} catch (PDOException $e) {
    logDebug("Database error", $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
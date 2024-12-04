<?php
header('Content-Type: application/json');
include 'db.php';

if (!isset($_GET['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User ID not specified']);
    exit;
}

$user_id = $_GET['user_id'];

try {
    $query = "SELECT m.*,
              CASE 
                  WHEN m.is_admin = 1 AND m.admin_id IS NOT NULL THEN 
                      (SELECT name FROM admin_accounts WHERE id = m.admin_id)
                  WHEN m.is_admin = 1 AND m.admin_id IS NULL THEN
                      'Admin'
                  ELSE 
                      CONCAT(u.firstName, ' ', u.lastName)
              END as sender_name,
              m.is_admin,
              m.timestamp
              FROM messages m
              LEFT JOIN user_accounts u ON m.sender_id = u.id
              WHERE m.sender_id = :user_id OR 
                    (m.is_admin = 1 AND m.sender_id = :user_id2)
              ORDER BY m.timestamp ASC";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':user_id2', $user_id);
    $stmt->execute();
    
    $messages = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $messages[] = [
            'id' => $row['id'],
            'sender_id' => $row['sender_id'],
            'admin_id' => $row['admin_id'],
            'message' => $row['message'],
            'is_admin' => (bool)$row['is_admin'],
            'timestamp' => strtotime($row['timestamp']) * 1000,
            'sender_name' => $row['sender_name']
        ];
    }
    
    echo json_encode($messages);
    
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
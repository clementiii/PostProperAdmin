<?php
header('Content-Type: application/json');
include 'db.php';

if (!isset($_GET['user_id']) || !isset($_GET['last_message_timestamp'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required parameters'
    ]);
    exit;
}

$user_id = $_GET['user_id'];
$last_timestamp = $_GET['last_message_timestamp'] / 1000; // Convert from milliseconds to seconds
$last_timestamp = date('Y-m-d H:i:s', $last_timestamp);

try {
    $query = "SELECT m.*,
              CASE 
                  WHEN m.is_admin = 1 AND m.admin_id IS NOT NULL THEN 
                      (SELECT name FROM admin_accounts WHERE id = m.admin_id)
                  WHEN m.is_admin = 1 AND m.admin_id IS NULL THEN
                      'Admin'
                  ELSE 
                      CONCAT(u.firstName, ' ', u.lastName)
              END as sender_name
              FROM messages m 
              LEFT JOIN user_accounts u ON m.sender_id = u.id 
              WHERE (m.sender_id = :user_id OR (m.is_admin = 1 AND m.sender_id IN 
                (SELECT DISTINCT sender_id FROM messages WHERE sender_id = :user_id2)))
              AND m.timestamp > :last_timestamp
              ORDER BY m.timestamp ASC";
              
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id2', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':last_timestamp', $last_timestamp);
    $stmt->execute();
    
    $new_messages = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $new_messages[] = [
            'id' => $row['id'],
            'sender_id' => $row['sender_id'],
            'message' => $row['message'],
            'timestamp' => strtotime($row['timestamp']) * 1000,
            'is_admin' => (bool)$row['is_admin'],
            'admin_id' => $row['admin_id'],
            'sender_name' => $row['sender_name']
        ];
    }
    
    echo json_encode([
        'hasNewMessages' => !empty($new_messages),
        'newMessages' => $new_messages
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
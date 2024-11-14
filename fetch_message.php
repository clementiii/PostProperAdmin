<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'];
$recipient = $_POST['recipient'];

$stmt = $conn->prepare("SELECT sender_id, message, timestamp FROM messages WHERE recipient_id = ? AND sender_id = ? AND seen = 0 ORDER BY timestamp ASC");
$stmt->bind_param("ii", $user_id, $recipient);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

// Mark messages as seen
$stmt = $conn->prepare("UPDATE messages SET seen = 1 WHERE recipient_id = ? AND sender_id = ?");
$stmt->bind_param("ii", $user_id, $recipient);
$stmt->execute();

echo json_encode($messages);
$stmt->close();
?>

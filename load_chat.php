<?php
session_start();
include 'db.php';

if (isset($_POST['recipient'])) {
    $user_id = $_SESSION['user_id'];
    $recipient = $_POST['recipient'];

    $stmt = $conn->prepare("SELECT sender_id, message, timestamp FROM messages WHERE (sender_id = ? AND recipient_id = ?) OR (sender_id = ? AND recipient_id = ?) ORDER BY timestamp ASC");
    $stmt->bind_param("iiii", $user_id, $recipient, $recipient, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode($messages);
    $stmt->close();
}
?>

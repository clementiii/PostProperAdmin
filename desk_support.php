<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php");
    exit;
}

function truncateMessage($message, $length = 35) {
    if (strlen($message) > $length) {
        return substr($message, 0, $length) . '...';
    }
    return $message;
}

// Fetch users who have messages
try {
    $query = "SELECT DISTINCT 
                u.id,
                u.firstName,
                u.lastName,
                u.user_profile_picture,
                (SELECT m2.message 
                 FROM messages m2 
                 WHERE (m2.sender_id = u.id OR (m2.is_admin = 1 AND m2.sender_id = u.id))
                 ORDER BY m2.timestamp DESC 
                 LIMIT 1) as latest_message,
                (SELECT m3.timestamp
                 FROM messages m3
                 WHERE (m3.sender_id = u.id OR (m3.is_admin = 1 AND m3.sender_id = u.id))
                 ORDER BY m3.timestamp DESC
                 LIMIT 1) as last_message_time
              FROM user_accounts u
              LEFT JOIN messages m ON u.id = m.sender_id
              WHERE EXISTS (
                SELECT 1 
                FROM messages m4 
                WHERE m4.sender_id = u.id
              )
              GROUP BY u.id
              ORDER BY last_message_time DESC";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $users_with_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $users_with_messages = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Desk Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/DeskSupport.css">
    <link rel="stylesheet" href="css/root.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
    <link rel="icon" type="image/png" href="assets/Southside.png">
</head>
<body>
<?php 
    $pageTitle = "Help Desk Chat";
    include 'header.php';
    include 'sidebar.php';
?> 

<div class="container-fluid" style="margin-left:18rem; max-width: calc(100% - 18rem);">
    <div class="row">
        <!-- Sidebar with user messages -->
        <div class="col-lg-3 col-md-4 bg-sidebar p-3">
            <h5 class="text-dark mb-3">Messages</h5>
            <div class="list-group" id="userList">
                <?php if (!empty($users_with_messages)): ?>
                    <?php foreach ($users_with_messages as $user): ?>
                        <?php
                            $timestamp = isset($user['last_message_time']) 
                                ? date('g:i A', strtotime($user['last_message_time'])) 
                                : '';
                            $truncatedMessage = truncateMessage($user['latest_message'] ?? 'No messages');
                        ?>
                        <div class="list-group-item list-group-item-action user-chat-item" 
                            data-user-id="<?php echo htmlspecialchars($user['id']); ?>"
                            data-user-name="<?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?>">
                            <div class="d-flex w-100">
                                <img src="<?php echo !empty($user['user_profile_picture']) ? 
                                    htmlspecialchars($user['user_profile_picture']) : 'assets/profile.jpg'; ?>" 
                                    class="rounded-circle me-3" width="40" height="40" alt="User">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="name d-flex justify-content-between align-items-center">
                                        <strong class="text-truncate me-2"><?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?></strong>
                                        <?php if ($timestamp): ?>
                                            <small class="text-muted flex-shrink-0"><?php echo $timestamp; ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-muted small mb-0 text-truncate" title="<?php echo htmlspecialchars($user['latest_message'] ?? 'No messages'); ?>">
                                        <?php echo htmlspecialchars($truncatedMessage); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted">
                        <p>No messages yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- Chat conversation area -->
        <div class="col-lg-9 col-md-8 chat-container">
            <div id="chat-window" class="chat-window p-4">
                <div class="text-center text-muted mt-5">
                    <p>Select a conversation to start chatting</p>
                </div>
            </div>
            <div class="chat-input d-flex align-items-center p-3 border-top">
                <input type="text" id="messageInput" class="form-control me-2" placeholder="Type your message here" disabled>
                <button id="sendMessageBtn" class="btn btn-purple" disabled>
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/chat.js"></script>
</body>
</html>
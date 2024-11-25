<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php");
    exit;
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

    // Debug log
    error_log("Found users with messages: " . print_r($users_with_messages, true));
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
                            // Format the timestamp
                            $timestamp = isset($user['last_message_time']) 
                                ? date('g:i A', strtotime($user['last_message_time'])) 
                                : '';
                        ?>
                        <div class="list-group-item list-group-item-action user-chat-item" 
                            data-user-id="<?php echo htmlspecialchars($user['id']); ?>"
                            data-user-name="<?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?>">
                            <div class="d-flex w-100">
                                <img src="<?php echo !empty($user['user_profile_picture']) ? 
                                    htmlspecialchars($user['user_profile_picture']) : 'assets/profile.jpg'; ?>" 
                                    class="rounded-circle me-3" width="40" height="40" alt="User">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong><?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?></strong>
                                        <?php if ($timestamp): ?>
                                            <small class="text-muted"><?php echo $timestamp; ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-muted small mb-0 text-truncate">
                                        <?php echo htmlspecialchars($user['latest_message'] ?? 'No messages'); ?>
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
        <div class="col-lg-9 col-md-8 chat-container" >
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
<script>
// Debug information
const debugMode = true;
function debugLog(message, data = null) {
    if (debugMode) {
        if (data) {
            console.log(message, data);
        } else {
            console.log(message);
        }
    }
}

let currentRecipient = null;
let lastMessageTimestamp = 0;

// Wait for DOM to be loaded
document.addEventListener('DOMContentLoaded', function() {
    debugLog('DOM loaded, initializing chat...');
    
    // Add click listeners to user items
    const userItems = document.querySelectorAll('.user-chat-item');
    userItems.forEach(item => {
        item.addEventListener('click', function() {
            debugLog('User item clicked');
            const userName = this.dataset.userName;
            const userId = this.dataset.userId;
            
            // Remove active class from all items
            userItems.forEach(i => i.classList.remove('active'));
            // Add active class to clicked item
            this.classList.add('active');
            
            debugLog('Loading chat for user:', userName);
            loadChat(userName, userId);
        });
    });

    // Add send button click handler
    document.getElementById('sendMessageBtn').addEventListener('click', function() {
        debugLog('Send button clicked');
        sendMessage();
    });

    // Add enter key handler
    document.getElementById('messageInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            debugLog('Enter key pressed');
            sendMessage();
        }
    });
});

function loadChat(userName, userId) {
    debugLog('Loading chat...', { userName, userId });
    currentRecipient = userName;
    
    const chatWindow = document.getElementById('chat-window');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendMessageBtn');

    chatWindow.innerHTML = '<div class="text-center"><p>Loading messages...</p></div>';
    messageInput.disabled = false;
    sendButton.disabled = false;

    fetch('loadChat.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `recipient=${encodeURIComponent(userName)}`
    })
    .then(response => {
        debugLog('Load chat response received');
        return response.json();
    })
    .then(data => {
        debugLog('Chat data:', data);
        displayMessages(data);
    })
    .catch(error => {
        console.error('Error loading chat:', error);
        chatWindow.innerHTML = '<div class="text-center text-danger"><p>Error loading messages</p></div>';
    });
}

function displayMessages(messages) {
    const chatWindow = document.getElementById('chat-window');
    chatWindow.innerHTML = '';

    if (Array.isArray(messages) && messages.length > 0) {
        messages.forEach(msg => {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('chat-message', msg.is_admin ? 'admin' : 'user');

            const messageContent = document.createElement('div');
            messageContent.classList.add('message-content');
            messageContent.textContent = msg.message;

            const messageTime = document.createElement('div');
            messageTime.classList.add('message-time');
            const date = new Date(msg.timestamp);
            messageTime.textContent = date.toLocaleTimeString();

            messageDiv.appendChild(messageContent);
            messageDiv.appendChild(messageTime);
            chatWindow.appendChild(messageDiv);
        });

        lastMessageTimestamp = new Date(messages[messages.length - 1].timestamp).getTime();
        chatWindow.scrollTop = chatWindow.scrollHeight;
    } else {
        chatWindow.innerHTML = '<div class="text-center"><p>No messages yet</p></div>';
    }
}

function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    debugLog('Sending message:', { recipient: currentRecipient, message });

    if (message && currentRecipient) {
        fetch('send_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `message=${encodeURIComponent(message)}&recipient=${encodeURIComponent(currentRecipient)}&is_admin=1`
        })
        .then(response => response.json())
        .then(data => {
            debugLog('Send message response:', data);
            if (data.status === 'success') {
                messageInput.value = '';
                loadChat(currentRecipient);
            } else {
                console.error('Error sending message:', data.message);
                alert('Error sending message: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error sending message. Please try again.');
        });
    }
}

// Check for new messages periodically
setInterval(() => {
    if (currentRecipient) {
        debugLog('Checking for new messages...');
        fetch('fetch_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `recipient=${encodeURIComponent(currentRecipient)}`
        })
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                debugLog('New messages found:', data);
                const latestMessageTime = new Date(data[data.length - 1].timestamp).getTime();
                if (latestMessageTime > lastMessageTimestamp) {
                    loadChat(currentRecipient);
                }
            }
        })
        .catch(error => console.error('Error checking messages:', error));
    }
}, 3000);
</script>
</body>
</html>
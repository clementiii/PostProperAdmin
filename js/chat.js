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
    initializeChatHandlers();
});

function initializeChatHandlers() {
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
}

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
    
    // Create a container for messages
    const messagesContainer = document.createElement('div');
    messagesContainer.className = 'messages-container';

    if (Array.isArray(messages) && messages.length > 0) {
        messages.forEach(msg => {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('chat-message', msg.is_admin ? 'admin' : 'user');

            // Add sender name div
            const senderName = document.createElement('div');
            senderName.classList.add('sender-name');
            senderName.textContent = msg.sender_name;
            
            const messageContent = document.createElement('div');
            messageContent.classList.add('message-content');
            messageContent.textContent = msg.message;

            const messageTime = document.createElement('div');
            messageTime.classList.add('message-time');
            
            // Create date objects
            const messageDate = new Date(msg.timestamp);
            const currentDate = new Date();
            
            // Format the time
            const timeString = messageDate.toLocaleTimeString([], { 
                hour: 'numeric', 
                minute: '2-digit', 
                hour12: true 
            });
            
            // Check if message is from a different day
            if (isSameDay(messageDate, currentDate)) {
                // If message is from today, show only time
                messageTime.textContent = timeString;
            } else {
                // If message is not from today, show date and time
                const dateString = messageDate.toLocaleDateString([], {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });
                messageTime.textContent = `${dateString} ${timeString}`;
            }

            messageDiv.appendChild(senderName);
            messageDiv.appendChild(messageContent);
            messageDiv.appendChild(messageTime);
            messagesContainer.appendChild(messageDiv);
        });

        lastMessageTimestamp = new Date(messages[messages.length - 1].timestamp).getTime();
    } else {
        const noMessages = document.createElement('div');
        noMessages.className = 'text-center';
        noMessages.innerHTML = '<p>No messages yet</p>';
        messagesContainer.appendChild(noMessages);
    }

    // Clear and add the new messages container
    chatWindow.innerHTML = '';
    chatWindow.appendChild(messagesContainer);
    
    // Scroll to bottom
    chatWindow.scrollTop = chatWindow.scrollHeight;
}

// Helper function to check if two dates are the same day
function isSameDay(date1, date2) {
    return date1.getDate() === date2.getDate() &&
           date1.getMonth() === date2.getMonth() &&
           date1.getFullYear() === date2.getFullYear();
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
                
                // Scroll to bottom after a short delay to ensure messages are loaded
                setTimeout(() => {
                    const chatWindow = document.getElementById('chat-window');
                    chatWindow.scrollTop = chatWindow.scrollHeight;
                }, 100);
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
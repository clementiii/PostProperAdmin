let currentRecipient = null;
let lastMessageTimestamp = 0;

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add click event listeners to all user items
    const userItems = document.querySelectorAll('.list-group-item');
    userItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            // Remove active class from all items
            userItems.forEach(i => i.classList.remove('active'));
            // Add active class to clicked item
            this.classList.add('active');
            
            // Get user name from the strong tag
            const userName = this.querySelector('strong').textContent;
            loadChat(userName);
        });
    });

    // Add event listener for send button
    const sendButton = document.getElementById('sendMessageBtn');
    if (sendButton) {
        sendButton.addEventListener('click', sendMessage);
    }

    // Add event listener for Enter key in message input
    const messageInput = document.getElementById('messageInput');
    if (messageInput) {
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }
});

function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();

    if (message && currentRecipient) {
        console.log('Sending message to:', currentRecipient);
        
        fetch("sendMessage.php", {
            method: "POST",
            headers: { 
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `message=${encodeURIComponent(message)}&recipient=${encodeURIComponent(currentRecipient)}&is_admin=1`
        })
        .then(response => response.json())
        .then(data => {
            console.log('Send message response:', data);
            if (data.status === "success") {
                messageInput.value = '';
                loadChat(currentRecipient);
            } else {
                console.error('Error response:', data);
                alert("Error: " + (data.message || 'Failed to send message'));
            }
        })
        .catch(error => {
            console.error("Error sending message:", error);
            alert("Error sending message. Please try again.");
        });
    }
}

function loadChat(username) {
    currentRecipient = username;
    console.log('Loading chat for:', username);

    const chatWindow = document.getElementById('chat-window');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendMessageBtn');

    // Show loading state
    chatWindow.innerHTML = '<div class="text-center"><p>Loading messages...</p></div>';
    
    // Enable input fields
    messageInput.disabled = false;
    sendButton.disabled = false;

    fetch("loadChat.php", {
        method: "POST",
        headers: { 
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `recipient=${encodeURIComponent(username)}`
    })
    .then(response => response.json())
    .then(data => {
        console.log('Received chat data:', data);
        chatWindow.innerHTML = '';

        if (Array.isArray(data) && data.length > 0) {
            data.forEach(msg => {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('chat-message');
                messageDiv.classList.add(msg.is_admin ? 'admin' : 'user');

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

            // Update last message timestamp
            lastMessageTimestamp = new Date(data[data.length - 1].timestamp).getTime();
            
            // Scroll to bottom
            chatWindow.scrollTop = chatWindow.scrollHeight;
        } else {
            chatWindow.innerHTML = '<div class="text-center"><p>No messages yet</p></div>';
        }
    })
    .catch(error => {
        console.error('Error loading chat:', error);
        chatWindow.innerHTML = '<div class="text-center text-danger"><p>Error loading messages</p></div>';
    });
}

// Function to check for new messages
function checkForNewMessages() {
    if (!currentRecipient) return;

    fetch("fetch_message.php", {
        method: "POST",
        headers: { 
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `recipient=${encodeURIComponent(currentRecipient)}`
    })
    .then(response => response.json())
    .then(data => {
        console.log('Checking for new messages:', data);
        if (Array.isArray(data) && data.length > 0) {
            const latestMessageTime = new Date(data[data.length - 1].timestamp).getTime();
            if (latestMessageTime > lastMessageTimestamp) {
                loadChat(currentRecipient);
            }
        }
    })
    .catch(error => {
        console.error('Error checking messages:', error);
    });
}

// Start polling for new messages
setInterval(checkForNewMessages, 3000);
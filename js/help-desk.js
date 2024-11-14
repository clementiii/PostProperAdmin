document.getElementById("sendMessageBtn").addEventListener("click", () => {
   const messageInput = document.getElementById("messageInput");
   const message = messageInput.value;
   const recipient = currentRecipient;

   if (message.trim() !== "") {
      fetch("sendMessage.php", {
         method: "POST",
         headers: { "Content-Type": "application/x-www-form-urlencoded" },
         body: `message=${encodeURIComponent(message)}&recipient=${recipient}`,
      })
         .then((response) => response.json())
         .then((data) => {
            if (data.status === "success") {
               loadChat(recipient); // Reload chat on success
               messageInput.value = ""; // Clear input field
            } else {
               alert("Error: " + data.message); // Show specific server error
            }
         })
         .catch((error) => {
            console.error("Error sending message:", error);
            alert("Error sending message. Please try again.");
         });
   }
});

let currentRecipient = null;

function loadChat(username) {
   currentRecipient = username;

   fetch("loadChat.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `recipient=${username}`,
   })
      .then((response) => response.json())
      .then((messages) => {
         const chatWindow = document.getElementById("chat-window");
         chatWindow.innerHTML = "";

         messages.forEach((msg) => {
            const messageDiv = document.createElement("div");
            messageDiv.classList.add(
               "chat-message",
               msg.sender_id === currentUser ? "user" : "admin"
            );

            const messageContent = document.createElement("div");
            messageContent.classList.add("message-content");
            messageContent.textContent = msg.message;

            const messageTime = document.createElement("small");
            messageTime.classList.add("message-time");
            messageTime.textContent = msg.timestamp;

            messageDiv.appendChild(messageContent);
            messageDiv.appendChild(messageTime);
            chatWindow.appendChild(messageDiv);
         });
      });
}
function checkForIncomingMessages() {
   if (!currentRecipient) return;

   fetch("fetchMessages.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `recipient=${currentRecipient}`,
   })
      .then((response) => response.json())
      .then((messages) => {
         if (messages.length > 0) {
            loadChat(currentRecipient); // Reload chat to include new messages
         }
      });
}

// Poll every 3 seconds for new messages
setInterval(checkForIncomingMessages, 3000);

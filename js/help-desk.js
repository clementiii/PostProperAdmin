const conversations = {
   "Pedro Manlangit": [
      { sender: "user", text: "Hello, how can I help you?", time: "9:00 AM" },
      {
         sender: "admin",
         text: "I need assistance with my account.",
         time: "9:15 AM",
      },
      {
         sender: "user",
         text: "Sure, what seems to be the issue?",
         time: "9:20 AM",
      },
   ],
   "Lito Wanderer": [
      { sender: "user", text: "Good morning!", time: "10:00 AM" },
      {
         sender: "admin",
         text: "Good morning! How can I assist you?",
         time: "10:05 AM",
      },
      {
         sender: "user",
         text: "I need help with my subscription.",
         time: "10:10 AM",
      },
   ],
   "Jules Pineda": [
      {
         sender: "user",
         text: "Is there a way to reset my password?",
         time: "11:00 AM",
      },
      {
         sender: "admin",
         text: "Yes, I can guide you through it.",
         time: "11:05 AM",
      },
      { sender: "user", text: "Thanks!", time: "11:10 AM" },
   ],
};

function loadChat(username) {
   const chatWindow = document.getElementById("chat-window");
   chatWindow.innerHTML = ""; // Clear previous chat messages

   if (username && conversations[username]) {
      const messages = conversations[username];

      messages.forEach((msg) => {
         const messageDiv = document.createElement("div");
         messageDiv.classList.add("chat-message", msg.sender);

         const messageContent = document.createElement("div");
         messageContent.classList.add("message-content");
         messageContent.textContent = msg.text;

         const messageTime = document.createElement("small");
         messageTime.classList.add("message-time");
         messageTime.textContent = msg.time;

         messageDiv.appendChild(messageContent);
         messageDiv.appendChild(messageTime);
         chatWindow.appendChild(messageDiv);
      });
   }
}

// Initially clear chat if no user is selected
document.addEventListener("DOMContentLoaded", () => {
   const chatWindow = document.getElementById("chat-window");
   chatWindow.innerHTML = ""; // Clear chat on page load
});

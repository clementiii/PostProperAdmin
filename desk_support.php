<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php"); // Redirect to the login page if not logged in
    exit;
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
    <script defer src="js/help-desk.js"></script>
</head>
<body>
<?php 
    $pageTitle = "Help Desk Chat";
    include 'header.php';
    include 'sidebar.php';
?> 

<div class="container-fluid" style="margin-left: 18rem; max-width: calc(100% - 18rem);" >
    <div class="row">
        <!-- Sidebar with user messages -->
        <div class="col-lg-3 col-md-4 bg-sidebar p-3">
            <h5 class="text-dark mb-3">Messages</h5>
            <div class="list-group">
               <a href="#" class="list-group-item list-group-item-action" onclick="loadChat('Pedro Manlangit')">
                    <div class="d-flex align-items-center">
                        <img src="assets/profile.jpg" class="rounded-circle me-3" width="40" height="40" alt="User">
                        <div>
                            <strong>Pedro Manlangit</strong>
                            <p class="text-muted small mb-0">Hello, pa paano mag-request</p>
                        </div>
                    </div>
                </a>
                <a href="#" class="list-group-item list-group-item-action" onclick="loadChat('Lito Wanderer')">
                    <div class="d-flex align-items-center">
                        <img src="assets/mckinley.jpg" class="rounded-circle me-3" width="40" height="40" alt="User">
                        <div>
                            <strong>Lito Wanderer</strong>
                            <p class="text-muted small mb-0">Lorem ipsum dolor sit amet</p>
                        </div>
                    </div>
                </a>
                <a href="#" class="list-group-item list-group-item-action" onclick="loadChat('Jules Pineda')">
                    <div class="d-flex align-items-center">
                        <img src="assets/profile.jpg" class="rounded-circle me-3" width="40" height="40" alt="User">
                        <div>
                            <strong>Jules Pineda</strong>
                            <p class="text-muted small mb-0">Lorem ipsum dolor sit amet</p>
                        </div>
                    </div>
                </a>
                <!-- Repeat above blocks for more users -->
            </div>
        </div>

        <!-- Chat conversation area -->
        <div class="col-lg-9 col-md-8 chat-container" >
            <div id="chat-window" class="chat-window p-4" >
                <!-- Messages will be dynamically loaded here -->
            </div>
            <div class="chat-input d-flex align-items-center p-3 border-top">
    <input type="text" id="messageInput" class="form-control me-2" placeholder="Type your message here">
    <button id="sendMessageBtn" class="btn btn-purple">
        <i class="fas fa-paper-plane"></i>
    </button>
</div>


        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

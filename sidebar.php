<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="css/Sidebar.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
<body>
<div class="sidebar">
    <div class="logo-section">
        <img src="assets/Southside.png" alt="Logo" class="logo" onclick="redirectToDashboard()">
        <h3 class="sidebar-title" onclick="redirectToDashboard()">Post Proper Southside</h3>
    </div>

    <?php
    $current_page = basename($_SERVER['PHP_SELF']);
    ?>

    <!-- Dashboard link -->
    <a href="dashboard.php" class="sidebar-link <?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>

    <!-- Admin Staff link -->
    <a href="admin_staff.php" class="sidebar-link <?= $current_page == 'admin_staff.php' ? 'active' : '' ?>">
        <i class="fas fa-users"></i> Admin Staff
    </a>

    <!-- Users link -->
    <a href="users.php" class="sidebar-link <?= $current_page == 'users.php' ? 'active' : '' ?>">
        <i class="fa-solid fa-user"></i> Users Account
    </a>

    <!-- Announcement link -->
    <a href="announcement.php" class="sidebar-link <?= $current_page == 'announcement.php' ? 'active' : '' ?>">
        <i class="fas fa-bullhorn"></i> Announcement
    </a>

    <!-- Documents link-->
    <a href="documents.php" class="sidebar-link <?= $current_page == 'documents.php' ? 'active' : '' ?>">
        <i class="fas fa-folder"></i>Documents
    </a>
    <!-- Reports link -->
    <a href="reports.php" class="sidebar-link <?= $current_page == 'reports.php' ? 'active' : '' ?>">
        <i class="fas fa-flag"></i> Reports
    </a>

    <!-- Desk Support link -->
    <a href="desk_support.php" class="sidebar-link <?= $current_page == 'desk_support.php' ? 'active' : '' ?>">
        <i class="fas fa-headset"></i> Desk Support
    </a>

    <!-- Logout link -->
    <a href="#" onclick="showLogoutModal(event)" class="btn-logout <?= $current_page == 'logout.php' ? 'active' : '' ?>">
        <i class="fas fa-sign-out-alt"></i> Log Out
    </a>
</div>

<script>
    function redirectToDashboard() {
        window.location.href = 'dashboard.php';
    }

    function showLogoutModal(event) {
        event.preventDefault();
        document.getElementById('logoutModal').style.display = 'block';
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').style.display = 'none';
    }

    function confirmLogout() {
        window.location.href = 'logout.php';
    }

    // Close modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('logoutModal');
        if (event.target == modal) {
            closeLogoutModal();
        }
    }

    // Close modal when pressing ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeLogoutModal();
        }
    });
</script>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Log Out</h2>
        <p>Are you sure you want log out?</p>
        <div class="modal-buttons">
            <button class="btn-cancel" onclick="closeLogoutModal()">No, cancel</button>
            <button class="btn-confirm" onclick="confirmLogout()">Yes, confirm</button>
        </div>
    </div>
</div>

<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    width: 400px;
    border-radius: 8px;
    position: relative;
    font-family: 'Poppins', sans-serif;
}

.close {
    position: absolute;
    right: 20px;
    top: 10px;
    font-size: 24px;
    cursor: pointer;
}

.modal h2 {
    margin-bottom: 10px;
    font-size: 24px;
    font-weight: 600;
}

.modal p {
    margin-bottom: 20px;
    font-size: 16px;
}

.modal-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.btn-cancel {
    padding: 8px 16px;
    border: 1px solid #ddd;
    background-color: #f5f5f5;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.btn-confirm {
    padding: 8px 16px;
    background-color: #6b21a8;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.btn-cancel:hover {
    background-color: #e5e5e5;
}

.btn-confirm:hover {
    background-color: #581c87;
}
</style>

</body>
</html>
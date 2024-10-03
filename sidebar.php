<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="css/Sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="sidebar">
    <div class="logo-section">
        <img src="assets/Southside.png" alt="Logo" class="logo">
            <h3>Barangay Post Proper Southside Information System</h3>
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

    <!-- Announcement link -->
    <a href="announcement.php" class="sidebar-link <?= $current_page == 'announcement.php' ? 'active' : '' ?>">
        <i class="fas fa-bullhorn"></i> Announcement
    </a>

    <!-- Dropdown for Documents -->
    <div class="dropdown">
        <button class="sidebar-link dropdown-toggle <?= in_array($current_page, ['barangayclearance.php', 'barangayId.php', 'certificateofIndigency.php', 'barangaycertificate.php']) ? 'active' : '' ?>">
            <i class="fas fa-folder"></i>Documents      <p style="visibility :hidden;">..................</p><i class="fa-solid fa-caret-down"></i>
        </button>
        <div class="dropdown-content">
            <a class="dropdown-item <?= $current_page == 'barangayclearance.php' ? 'active' : '' ?>" href="barangayclearance.php">
                <i class="fas fa-file-alt"></i> Barangay Clearance
            </a>
            <a class="dropdown-item <?= $current_page == 'barangayId.php' ? 'active' : '' ?>" href="barangayId.php">
                <i class="fas fa-id-card"></i> Barangay ID
            </a>
            <a class="dropdown-item <?= $current_page == 'certificateofIndigency.php' ? 'active' : '' ?>" href="certificateofIndigency.php">
                <i class="fas fa-certificate"></i> Certificate of Indigency
            </a>
            <a class="dropdown-item <?= $current_page == 'barangaycertificate.php' ? 'active' : '' ?>" href="barangaycertificate.php">
                <i class="fas fa-award"></i> Barangay Certificate
            </a>
        </div>
    </div>

    <!-- Reports link -->
    <a href="reports.php" class="sidebar-link <?= $current_page == 'reports.php' ? 'active' : '' ?>">
        <i class="fas fa-flag"></i> Reports
    </a>

    <!-- Desk Support link -->
    <a href="desk_support.php" class="sidebar-link <?= $current_page == 'desk_support.php' ? 'active' : '' ?>">
        <i class="fas fa-headset"></i> Desk Support
    </a>

    <!-- Logout link -->
    <a href="logout.php" class="sidebar-link <?= $current_page == 'logout.php' ? 'active' : '' ?>">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>

</body>
</html>
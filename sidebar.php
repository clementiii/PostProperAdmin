<!-- sidebar.php -->
<div class="sidebar">
    <div>
        <img src="path-to-your-logo.png" alt="Logo">
        <h2>Post Proper Southside</h2>
        <?php
        $current_page = basename($_SERVER['PHP_SELF']);
        ?>
        <a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">Dashboard</a>
        <a href="admin_staff.php" class="<?= $current_page == 'admin_staff.php' ? 'active' : '' ?>">Admin Staff</a>
        <a href="announcement.php" class="<?= $current_page == 'announcement.php' ? 'active' : '' ?>">Announcement</a>
        <a href="documents.php" class="<?= $current_page == 'documents.php' ? 'active' : '' ?>">Documents</a>
        <a href="reports.php" class="<?= $current_page == 'reports.php' ? 'active' : '' ?>">Reports</a>
        <a href="desk_support.php" class="<?= $current_page == 'desk_support.php' ? 'active' : '' ?>">Desk Support</a>
        <a href="logout.php" class="logout <?= $current_page == 'logout.php' ? 'active' : '' ?>">Logout</a> <!-- Logout button below Desk Support -->
    </div>
</div>

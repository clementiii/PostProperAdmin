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
    <title>Barangay Announcements</title>
    <link rel="stylesheet" href="css/Announcement.css">
</head>
<body>
<?php 
    $pageTitle = "Announcements";
    include 'header.php';
    ?>
<?php include 'sidebar.php'; ?> 
<!-- announcements.php -->
<!-- announcements.php -->
<div class="main-content">
    <div class="content-layout">
        <!-- Left Section -->
        <div class="left-section">
            <div class="white-card">
                <h2>Add New Announcement</h2>
                <form action="process_announcement.php" method="POST" enctype="multipart/form-data">
                    <div class="input-group">
                        <label>Announcement Title:</label>
                        <input type="text" name="title" class="full-width-input" required>
                    </div>

                    <div class="input-group">
                        <label>Description</label>
                        <textarea name="description" class="full-width-input" rows="8" required></textarea>
                    </div>

                    <div class="upload-section">
                        <div class="upload-box">
                            <div class="upload-icon">↑</div>
                            <div class="upload-text">Upload Image Here</div>
                            <input type="file" name="image" class="file-input" accept="image/*">
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn-save">Save</button>
                        <button type="reset" class="btn-clear">Clear</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <div class="white-card">
                <h2>Recent Posts</h2>
                <div class="posts-list">
                    <?php
                    // Sample data - replace with database query
                    $posts = [
                        ['title' => 'Sample Announcement 1', 'date' => 'October 12'],
                        ['title' => 'Sample Announcement 2', 'date' => 'October 10']
                    ];

                    foreach ($posts as $post) {
                        echo '<div class="post-item">
                                <div class="post-content">
                                    <h3>' . htmlspecialchars($post['title']) . '</h3>
                                    <span class="post-date">' . htmlspecialchars($post['date']) . '</span>
                                </div>
                                <div class="post-actions">
                                    <button class="btn-edit">Edit</button>
                                    <button class="btn-delete">Delete</button>
                                </div>
                              </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
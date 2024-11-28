<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php"); // Redirect to the login page if not logged in
    exit;
}

include 'db.php'; // Make sure this file connects to your `pps_barangay_system` database

// Fetch recent announcements from the database
$query = "SELECT id, announcement_title, description_text, announcement_images, created_at FROM barangay_announcements ORDER BY created_at DESC LIMIT 10";
$stmt = $conn->prepare($query);
$stmt->execute();
$announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Announcements</title>
    <link rel="stylesheet" href="css/Announcement.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/Southside.png">

    <style>
    .custom-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .custom-modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 0;
        border: 1px solid #888;
        width: 400px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .custom-modal-header {
        padding: 15px 20px;
        background-color: #61009F;
        color: white;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .custom-modal-body {
        padding: 20px;
    }

    .custom-modal-footer {
        padding: 15px 20px;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .close-modal {
        color: white;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close-modal:hover {
        color: #f0f0f0;
    }
    </style>
</head>
<body>
<?php 
    $pageTitle = "Announcements";
    include 'header.php';
?>
<?php include 'sidebar.php'; ?> 

<div class="main-content">
    <div class="content-layout">
        <div class="left-section">
            <div class="white-card">
                <h2>Add New Announcement</h2>
                <form action="process_announcement.php" method="POST" enctype="multipart/form-data">
                    <div class="input-group">
                        <label for="title">Announcement Title:</label>
                        <input type="text" name="title" id="title" class="full-width-input" required>
                    </div>
                    <div class="input-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="full-width-input" rows="8" required></textarea>
                    </div>
                    <div class="upload-section">
                        <div class="upload-box">
                            <div class="upload-icon">↑</div>
                            <div class="upload-text">Upload Image Here (Maximum 5 images)</div>
                            <input type="file" name="images[]" id="images" class="file-input" accept="image/*" multiple onchange="validateImageCount(this)">
                        </div>
                        <div id="imageCountWarning" style="color: red; margin-top: 10px; display: none;">
                            Maximum 5 images allowed. Please remove some images before adding more.
                        </div>
                    </div>

                    <!-- Container to display uploaded images with remove icons -->
                    <div id="image-preview-container" class="d-flex flex-wrap mt-3"></div>

                    <div class="button-group">
                        <button type="button" class="btn-save" onclick="showPublishModal()">Post</button>
                        <button type="reset" class="btn-clear">Clear</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="right-section">
            <div class="white-card">
                <h2>Recent Posts</h2>
                <div class="posts-list">
                <?php
                    foreach ($announcements as $announcement) {
                        $formattedDate = date("F d, Y", strtotime($announcement['created_at']));
                        echo '<div class="post-item">
                                <div class="post-content">
                                    <h3>' . htmlspecialchars($announcement['announcement_title']) . '</h3>
                                    <span class="post-date">' . htmlspecialchars($formattedDate) . '</span>
                                </div>
                                <div class="post-actions">
                                    <a href="post_edit.php?id=' . $announcement['id'] . '" class="btn-edit">Edit</a>
                                    <a href="process_announcement.php?action=delete&id=' . $announcement['id'] . '" class="btn-delete">Delete</a>
                                </div>
                            </div>';
                    }
                ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Publish Modal -->
<div id="publishModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="modal-title">Publish Announcement Confirmation</h5>
            <span class="close-modal">&times;</span>
        </div>
        <div class="custom-modal-body">
            Are you sure you want to publish this announcement?
        </div>
        <div class="custom-modal-footer">
            <button class="btn btn-secondary close-modal">No, cancel</button>
            <button class="btn btn-primary" id="confirmPublish">Yes, confirm</button>
        </div>
    </div>
</div>

<!-- Custom Delete Modal -->
<div id="deleteModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="modal-title">Delete Post Confirmation</h5>
            <span class="close-modal">&times;</span>
        </div>
        <div class="custom-modal-body">
            Are you sure you want to delete this post? This action cannot be undone.
        </div>
        <div class="custom-modal-footer">
            <button class="btn btn-secondary close-modal">No, cancel</button>
            <button class="btn btn-danger" id="confirmDelete">Yes, confirm</button>
        </div>
    </div>
</div>

<script>
function showPublishModal() {
    document.getElementById('publishModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

// Update delete links to use custom modal
document.querySelectorAll('.btn-delete').forEach(button => {
    button.onclick = function(e) {
        e.preventDefault();
        const deleteUrl = this.href;
        document.getElementById('confirmDelete').onclick = function() {
            window.location.href = deleteUrl;
        };
        document.getElementById('deleteModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    };
});

// Handle modal closes
document.querySelectorAll('.close-modal').forEach(button => {
    button.onclick = function() {
        document.getElementById('publishModal').style.display = 'none';
        document.getElementById('deleteModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
});

// Close modals when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('custom-modal')) {
        event.target.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Handle publish confirmation
document.getElementById('confirmPublish').onclick = function() {
    const input = document.getElementById('images');
    if (input.files.length > 5) {
        alert('You can only upload a maximum of 5 images. Please remove some images before publishing.');
        return false;
    }
    document.querySelector('form').submit();
}

function validateImageCount(input) {
    const maxImages = 5;
    const warningDiv = document.getElementById('imageCountWarning');
    
    if (input.files.length > maxImages) {
        warningDiv.style.display = 'block';
        input.value = ''; // Clear the selection
        return false;
    }
    
    warningDiv.style.display = 'none';
    return true;
}
</script>

</body>
</html>
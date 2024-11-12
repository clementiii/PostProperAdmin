<?php
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}
?>

<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php"); // Redirect to the login page if not logged in
    exit;
}

include 'db.php'; // Make sure this file connects to your `pps_barangay_system` database

// Fetch recent announcements from the database
$query = "SELECT id, announcement_title, description_text, created_at, announcement_images FROM barangay_announcements ORDER BY created_at DESC LIMIT 10";
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
    <style>
        /* Styling for the preview container */
        #image-preview-container, #edit-image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .image-preview {
            position: relative;
            width: 100px;
            height: 100px;
        }
        
        .image-preview img {
            width: 100px;
            height: 100px;
            border-radius: 4px;
            object-fit: cover;
        }
        
        .btn-close {
            color: #fff;
            background-color: #dc3545; /* Red color for remove button */
            border-radius: 50%;
            font-size: 12px;
            cursor: pointer;
            position: absolute;
            top: -5px;
            right: -5px;
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
                            <input type="file" name="images[]" class="file-input" accept="image/*" multiple>
                        </div>
                    </div>

                    <!-- Container to display uploaded images with remove icons -->
                    <div id="image-preview-container" class="d-flex flex-wrap mt-3"></div>

                    <div class="button-group">
                        <button type="button" class="btn-save" data-bs-toggle="modal" data-bs-target="#publishModal">Post</button>
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
                        // Format date for display
                        $formattedDate = date("F d, Y", strtotime($announcement['created_at']));
                        
                        // Display each announcement
                        echo '<div class="post-item">
                                <div class="post-content">
                                    <h3>' . htmlspecialchars($announcement['announcement_title']) . '</h3>
                                    <span class="post-date">' . htmlspecialchars($formattedDate) . '</span>
                                </div>
                                <div class="post-actions">
                                    <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' . $announcement['id'] . '" data-title="' . htmlspecialchars($announcement['announcement_title']) . '" data-description="' . htmlspecialchars($announcement['description_text']) . '">Edit</button>
                                    <button class="btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                                </div>
                              </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Publish Confirmation Modal -->
<div class="modal fade" id="publishModal" tabindex="-1" aria-labelledby="publishModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="publishModalLabel">Publish Announcement Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to publish this announcement?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, cancel</button>
                <button type="button" class="btn btn-primary" id="confirmPublish">Yes, confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Announcement Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="edit_announcement.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="post_id" id="editPostId">
                    <div class="input-group">
                        <label>Announcement Title:</label>
                        <input type="text" name="title" id="editTitle" class="full-width-input" required>
                    </div>
                    <div class="input-group">
                        <label>Description</label>
                        <textarea name="description" id="editDescription" class="full-width-input" rows="8" required></textarea>
                    </div>
                    
                    <!-- Display existing images with remove options -->
                    <div id="current-images-container" class="d-flex flex-wrap mt-3">
                        <!-- Existing images will be populated here by JavaScript -->
                    </div>

                    <div class="upload-section">
                        <div class="upload-box">
                            <div class="upload-icon">↑</div>
                            <div class="upload-text">Upload New Images</div>
                            <input type="file" name="images[]" class="edit-file-input" accept="image/*" multiple>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Post Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this post? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Yes, confirm</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/announcement.js"></script>
</body>
</html>

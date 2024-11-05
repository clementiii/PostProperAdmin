<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Announcements</title>
    <link rel="stylesheet" href="css/Announcement.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php 
    $pageTitle = "Announcements";
    include 'header.php';
?>
<?php include 'sidebar.php'; ?> 

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
                        <!-- Publish button triggers the modal -->
                        <button type="button" class="btn-save" data-bs-toggle="modal" data-bs-target="#publishModal">Publish</button>
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
<script>
    document.getElementById('confirmPublish').addEventListener('click', function() {
        // Handle publish confirmation logic here (submit the form or make an AJAX request)
        document.querySelector('form').submit(); // Example to submit the form
    });

    document.getElementById('confirmDelete').addEventListener('click', function() {
        // Handle delete confirmation logic here (e.g., AJAX request to delete the post)
        alert('Post deleted'); // Placeholder for delete action
    });
</script>
</body>
</html>

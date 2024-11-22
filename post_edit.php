<?php
session_start();
include 'db.php'; // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php");
    exit;
}

// Get the announcement ID from the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $announcementId = $_GET['id'];

    // Fetch the existing announcement details
    $query = "SELECT id, announcement_title, description_text, announcement_images FROM barangay_announcements WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $announcementId, PDO::PARAM_INT);
    $stmt->execute();
    $announcement = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if announcement exists
    if (!$announcement) {
        echo 'Announcement not found!';
        exit;
    }
} else {
    echo 'Invalid announcement ID!';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Announcement</title>
    <link rel="stylesheet" href="css/post-edit.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/Southside.png">
    <style>
        .back-button {
            position: fixed;
            top: 80px;
            left: 280px;
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 1000;
        }
        
        .back-button:hover {
            background-color: #5a6268;
            color: white;
            text-decoration: none;
        }

        .back-button i {
            margin-right: 5px;
        }

        .image-limit-warning {
            color: red;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>
<?php 
$pageTitle = "Edit Announcement";
include 'header.php'; ?>
<?php include 'sidebar.php';
?>


<div class="main-content">
    <a href="announcement.php" class="back-button"><i class="fas fa-arrow-left"></i> Back</a>
    <div class="content-layout">
        <div class="left-section">
            <div class="white-card">
                <h2>Edit Announcement</h2>
                <form action="process_announcement.php" method="POST" enctype="multipart/form-data" id="announcementForm">
                    <input type="hidden" name="id" value="<?php echo $announcement['id']; ?>" />
                    <div class="input-group">
                        <label>Announcement Title:</label>
                        <input type="text" name="title" class="full-width-input" value="<?php echo htmlspecialchars($announcement['announcement_title']); ?>" required>
                    </div>
                    <div class="input-group">
                        <label>Description:</label>
                        <textarea name="description" class="full-width-input" rows="8" required><?php echo htmlspecialchars($announcement['description_text']); ?></textarea>
                    </div>
                    <div class="upload-section">
                        <div class="upload-box">
                            <div class="upload-icon">↑</div>
                            <div class="upload-text">Upload Image Here (Maximum 5 images)</div>
                            <input type="file" name="images[]" class="file-input" accept="image/*" multiple>
                        </div>
                        <div class="image-limit-warning" id="imageLimitWarning">
                            Maximum 5 images allowed. Please remove some images before adding more.
                        </div>
                    </div>
                    <div id="image-preview-container" class="d-flex flex-wrap mt-3">
                        <?php
                        // Decode JSON formatted images
                        $images = json_decode($announcement['announcement_images'], true);
                        
                        if (!empty($images)) {
                            foreach ($images as $image) {
                                $imagePath = trim($image);  // Remove extra spaces
                                if (file_exists($imagePath)) {
                                    echo '<div class="image-preview position-relative me-2 mb-2">
                                            <img src="' . htmlspecialchars($imagePath) . '" class="img-fluid" alt="Announcement Image">
                                            <label class="form-check-label">
                                                <input type="checkbox" name="remove_images[]" value="' . htmlspecialchars($imagePath) . '" class="form-check-input"> Remove
                                            </label>
                                        </div>';
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="button-group">
                        <button type="submit" name="action" value="update" class="btn-save">Save Changes</button>
                        <a href="announcement.php" class="btn-clear">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('announcementForm');
    const fileInput = form.querySelector('input[type="file"]');
    const warningDiv = document.getElementById('imageLimitWarning');
    const imagePreviewContainer = document.getElementById('image-preview-container');

    function updateImageCount() {
        const existingImages = imagePreviewContainer.querySelectorAll('.image-preview').length;
        const newImages = fileInput.files.length;
        const totalImages = existingImages + newImages;
        
        if (totalImages > 5) {
            warningDiv.style.display = 'block';
            fileInput.value = ''; // Clear the file input
        } else {
            warningDiv.style.display = 'none';
        }
    }

    fileInput.addEventListener('change', updateImageCount);

    // Update count when checkboxes for removal are clicked
    imagePreviewContainer.addEventListener('change', function(e) {
        if (e.target.type === 'checkbox') {
            updateImageCount();
        }
    });

    form.addEventListener('submit', function(e) {
        const existingImages = imagePreviewContainer.querySelectorAll('.image-preview').length;
        const newImages = fileInput.files.length;
        const removingImages = form.querySelectorAll('input[name="remove_images[]"]:checked').length;
        const totalImages = existingImages + newImages - removingImages;

        if (totalImages > 5) {
            e.preventDefault();
            alert('You can only have a maximum of 5 images. Please remove some images before saving.');
        }
    });
});
</script>
</body>
</html>
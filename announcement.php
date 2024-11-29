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
        /* Additional styles for image preview */
        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .image-preview {
            position: relative;
            width: 100px;
            height: 100px;
        }
        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }
        .image-preview .remove-image {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
        }

        .is-invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    
    .text-danger {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
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
    <!-- The error message for title will be dynamically added here -->
</div>
<div class="input-group">
    <label for="description">Description</label>
    <textarea name="description" id="description" class="full-width-input" rows="8" required></textarea>
    <!-- The error message for description will be dynamically added here -->
</div>
                    <div class="upload-section">
                        <div class="upload-box">
                            <div class="upload-icon">↑</div>
                            <div class="upload-text">Upload Image Here (Maximum 5 images)</div>
                            <input type="file" name="images[]" id="images" class="file-input" accept="image/*" multiple onchange="previewImages(this)">
                        </div>
                        <div id="imageCountWarning" class="text-danger mt-2" style="display: none;">
                            Maximum 5 images allowed. Please remove some images before adding more.
                        </div>
                    </div>

                    <!-- Container to display uploaded images with remove icons -->
                    <div id="image-preview-container" class="image-preview-container"></div>

                    <div class="button-group">
                        <button type="button" class="btn-save" onclick="showPublishModal()">Post</button>
                        <button type="reset" class="btn-clear" onclick="clearImagePreview()">Clear</button>
                    </div>
                </form>
            </div>
        </div>
   <!-- Modal HTML -->
<div id="publishModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="modal-title">Confirm Publication</h5>
            <i class="fas fa-times close-modal" onclick="document.getElementById('publishModal').style.display='none'"></i>
        </div>
        <div class="custom-modal-body">
            Are you sure you want to post this announcement?
        </div>
        <div class="custom-modal-footer">
            <button class="btn-cancel close-modal" onclick="document.getElementById('publishModal').style.display='none'">Cancel</button>
            <button class="btn-post" onclick="confirmPublish()">Post</button>
        </div>
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

<!-- Existing modals remain the same -->

<script>
   function validateForm() {
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');
    
    let titleErrorContainer = document.getElementById('title-error');
    if (!titleErrorContainer) {
        titleErrorContainer = document.createElement('div');
        titleErrorContainer.id = 'title-error';
        titleErrorContainer.className = 'text-danger mb-2';
        titleInput.parentNode.insertBefore(titleErrorContainer, titleInput.nextSibling);
    }

    let descriptionErrorContainer = document.getElementById('description-error');
    if (!descriptionErrorContainer) {
        descriptionErrorContainer = document.createElement('div');
        descriptionErrorContainer.id = 'description-error';
        descriptionErrorContainer.className = 'text-danger mb-2';
        descriptionInput.parentNode.insertBefore(descriptionErrorContainer, descriptionInput.nextSibling);
    }

    titleErrorContainer.textContent = '';
    descriptionErrorContainer.textContent = '';

    let isValid = true;

    if (titleInput.value.trim() === '') {
        titleErrorContainer.textContent = 'Announcement Title is required.';
        titleInput.classList.add('is-invalid');
        isValid = false;
    } else {
        titleInput.classList.remove('is-invalid');
        titleErrorContainer.textContent = '';
    }

    if (descriptionInput.value.trim() === '') {
        descriptionErrorContainer.textContent = 'Description is required.';
        descriptionInput.classList.add('is-invalid');
        isValid = false;
    } else {
        descriptionInput.classList.remove('is-invalid');
        descriptionErrorContainer.textContent = '';
    }

    if (!isValid) {
        return false;
    }

    showPublishModal();
    return true;
}

function showPublishModal() {
    const input = document.getElementById('images');
    if (input.files.length > 5) {
        alert('You can only upload a maximum of 5 images. Please remove some images before publishing.');
        return false;
    }
    
    document.getElementById('publishModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
    return false;
}

function confirmPublish() {
    document.querySelector('form').submit();
}

function previewImages(input) {
    const previewContainer = document.getElementById('image-preview-container');
    const warningDiv = document.getElementById('imageCountWarning');
    const maxImages = 5;

    previewContainer.innerHTML = '';

    if (input.files.length > maxImages) {
        warningDiv.style.display = 'block';
        input.value = '';
        return;
    }

    warningDiv.style.display = 'none';

    Array.from(input.files).forEach((file, index) => {
        if (index < maxImages) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.classList.add('image-preview');
                previewDiv.innerHTML = `
                    <img src="${e.target.result}" alt="Image Preview">
                    <span class="remove-image" onclick="removeImage(${index})">&times;</span>
                `;
                previewContainer.appendChild(previewDiv);
            }
            
            reader.readAsDataURL(file);
        }
    });
}

function removeImage(index) {
    const input = document.getElementById('images');
    const dt = new DataTransfer();
    const files = input.files;
    
    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            dt.items.add(files[i]);
        }
    }
    
    input.files = dt.files;
    previewImages(input);
}

function clearForm() {
    document.getElementById('title').value = '';
    document.getElementById('description').value = '';
    
    const previewContainer = document.getElementById('image-preview-container');
    const imageInput = document.getElementById('images');
    previewContainer.innerHTML = '';
    imageInput.value = '';

    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');
    const titleErrorContainer = document.getElementById('title-error');
    const descriptionErrorContainer = document.getElementById('description-error');

    if (titleErrorContainer) {
        titleErrorContainer.textContent = '';
    }
    if (descriptionErrorContainer) {
        descriptionErrorContainer.textContent = '';
    }

    titleInput.classList.remove('is-invalid');
    descriptionInput.classList.remove('is-invalid');

    const imageCountWarning = document.getElementById('imageCountWarning');
    if (imageCountWarning) {
        imageCountWarning.style.display = 'none';
    }
}

function clearImagePreview() {
    const previewContainer = document.getElementById('image-preview-container');
    const input = document.getElementById('images');
    previewContainer.innerHTML = '';
    input.value = '';
}

// Event Listeners
document.querySelector('form').onsubmit = function(e) {
    if (!validateForm()) {
        e.preventDefault();
    }
};

document.querySelector('.btn-save').onclick = function(e) {
    e.preventDefault();
    if (validateForm()) {
        showPublishModal();
    }
};

document.querySelector('.btn-clear').onclick = function(e) {
    e.preventDefault();
    clearForm();
};

// Existing modal and other script functions remain the same
</script>

</body>
</html>
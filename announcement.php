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
            <button class="btn-cancel1 close-modal" onclick="document.getElementById('publishModal').style.display='none'">Cancel</button>
            <button class="btn-post" onclick="confirmPublish()">Post</button>
        </div>
    </div>
</div>

         <div class="right-section">
            <div class="white-card">
                <h2>Recent Posts</h2>
                <div class="posts-list">
                <?php
                    // Display success message if set
                    if (isset($_SESSION['success_message'])) {
                        echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
                        unset($_SESSION['success_message']);
                    }
                    
                    // Display error message if set
                    if (isset($_SESSION['error_message'])) {
                        echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
                        unset($_SESSION['error_message']);
                    }

                    foreach ($announcements as $announcement) {
                        $formattedDate = date("F d, Y", strtotime($announcement['created_at']));
                        echo '<div class="post-item">
                                <div class="post-content">
                                    <h3>' . htmlspecialchars($announcement['announcement_title']) . '</h3>
                                    <span class="post-date">' . htmlspecialchars($formattedDate) . '</span>
                                </div>
                                <div class="post-actions">
                                    <a href="post_edit.php?id=' . $announcement['id'] . '" class="btn-edit">Edit</a>
                                    <a href="#" class="btn-delete" data-id="' . $announcement['id'] . '">Delete</a>
                                </div>
                            </div>';
                    }
                ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="deleteModal" class="custom-modal1">
    <div class="custom-modal-content1">
        <div class="custom-modal-header1">
            <h5 class="modal-title1">Confirm Deletion</h5>
            <i class="fas fa-times close-modal" onclick="closeDeleteModal()"></i>
        </div>
        <div class="custom-modal-body1">
            Are you sure you want to delete this announcement? This action cannot be undone.
            <br><br>
            <strong>Note:</strong> This will permanently delete the announcement and all associated images.
        </div>
        <div class="custom-modal-footer1">
            <button class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
            <button class="btn-delete-modal" onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>



                    


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

let deleteItemId = null;
let isProcessing = false;

function showDeleteModal(id) {
    deleteItemId = id;
    document.getElementById('deleteModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
    return false;
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    deleteItemId = null;
}

function confirmDelete() {
    if (deleteItemId && !isProcessing) {
        isProcessing = true;
        const deleteButton = document.querySelector('#deleteModal .btn-delete-modal');
        deleteButton.textContent = 'Deleting...';
        deleteButton.disabled = true;
        
        window.location.href = `process_announcement.php?action=delete&id=${deleteItemId}`;
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const deleteModal = document.getElementById('deleteModal');
    const publishModal = document.getElementById('publishModal');
    
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
    if (event.target === publishModal) {
        publishModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Add event listeners when the document loads
document.addEventListener('DOMContentLoaded', function() {
    // Update all delete links to use the modal
    const deleteLinks = document.querySelectorAll('.btn-delete');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            showDeleteModal(id);
        });
    });
    
    // Hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    });
});
</script>

</body>
</html>
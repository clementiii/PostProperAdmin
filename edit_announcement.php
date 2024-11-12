<?php
include 'db.php';

$postId = $_POST['post_id'];
$title = $_POST['title'];
$description = $_POST['description'];

// Fetch existing images from the database
$sql = "SELECT announcement_images FROM barangay_announcements WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
$existingImages = json_decode($post['announcement_images'], true);

// Remove selected images
if (!empty($_POST['remove_images'])) {
    $existingImages = array_diff($existingImages, $_POST['remove_images']);
}

// Handle new image uploads
$upload_dir = 'uploads/announcements/';
foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
    $image_name = basename($_FILES['images']['name'][$key]);
    $target_path = $upload_dir . time() . '_' . $image_name;

    if (move_uploaded_file($tmp_name, $target_path)) {
        $existingImages[] = $target_path;
    }
}

// Update database with modified images array
$image_paths_json = json_encode($existingImages);

$sql = "UPDATE barangay_announcements SET announcement_title = ?, description_text = ?, announcement_images = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$title, $description, $image_paths_json, $postId]);

header("Location: announcement.php");
exit;
?>

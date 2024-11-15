<?php
include 'db.php';

$postId = $_POST['post_id'];

// Fetch the existing post to delete associated images
$sql = "SELECT announcement_images FROM barangay_announcements WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if ($post) {
    $images = json_decode($post['announcement_images'], true);
    foreach ($images as $image) {
        if (file_exists($image)) {
            unlink($image); // Delete each image from the server
        }
    }

    // Delete the post
    $sql = "DELETE FROM barangay_announcements WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$postId]);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Post not found']);
}
?>

<?php
session_start();
include 'db.php'; // Ensure this file connects to your pps_barangay_system database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $created_at = date("Y-m-d H:i:s");
    $posted_at = date("Y-m-d H:i:s");

    // Prepare for image uploads
    $image_paths = [];
    if (!empty($_FILES['images']['name'][0])) {
        $upload_dir = 'uploads/announcements/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $image_name = basename($_FILES['images']['name'][$key]);
            $target_path = $upload_dir . time() . '_' . $image_name;

            if (move_uploaded_file($tmp_name, $target_path)) {
                $image_paths[] = $target_path;
            }
        }
    }

    // Convert the array of image paths into JSON format
    $image_paths_json = json_encode($image_paths);

    // Check if it's an edit or a new post
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update existing announcement
        $announcement_id = $_POST['id'];
        $sql = "UPDATE barangay_announcements 
                SET announcement_title = :title, description_text = :description, 
                    announcement_images = :images, created_at = :created_at, posted_at = :posted_at 
                WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $announcement_id);
    } else {
        // Insert new announcement
        $sql = "INSERT INTO barangay_announcements (announcement_title, description_text, announcement_images, created_at, posted_at)
                VALUES (:title, :description, :images, :created_at, :posted_at)";
        $stmt = $conn->prepare($sql);
    }

    // Bind the form data to the query
    $stmt->bindValue(':title', $title);
    $stmt->bindValue(':description', $description);
    $stmt->bindValue(':images', $image_paths_json);
    $stmt->bindValue(':created_at', $created_at);
    $stmt->bindValue(':posted_at', $posted_at);

    // Execute the query and handle success or error
    if ($stmt->execute()) {
        if (isset($announcement_id)) {
            $_SESSION['success_message'] = "Announcement updated successfully!";
        } else {
            $_SESSION['success_message'] = "Announcement posted successfully!";
        }
    } else {
        $_SESSION['error_message'] = "Error processing the announcement. Please try again.";
    }

    // Close the statement and connection
    $stmt = null;
    $conn = null;

    // Redirect back to the announcements page
    header("Location: announcement.php");
    exit;
}
?>

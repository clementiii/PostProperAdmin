<?php
session_start();
include 'db.php'; // Ensure this file connects to your pps_barangay_system database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Insert into database
    $sql = "INSERT INTO barangay_announcements (announcement_title, description_text, announcement_images, created_at, posted_at)
            VALUES (:title, :description, :images, :created_at, :posted_at)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':title', $title);
    $stmt->bindValue(':description', $description);
    $stmt->bindValue(':images', $image_paths_json);
    $stmt->bindValue(':created_at', $created_at);
    $stmt->bindValue(':posted_at', $posted_at);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Announcement posted successfully!";
    } else {
        $_SESSION['error_message'] = "Error posting announcement. Please try again.";
    }

    // Close the statement
    $stmt = null;

    // Close the connection
    $conn = null;

    header("Location: announcement.php");
    exit;
}

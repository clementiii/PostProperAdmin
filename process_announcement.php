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

            // Fetch existing images from the database
            $query = "SELECT announcement_images FROM barangay_announcements WHERE id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id', $announcement_id, PDO::PARAM_INT);
            $stmt->execute();
            $existing_images = json_decode($stmt->fetchColumn(), true);

            // Handle image removals
            $remove_images = isset($_POST['remove_images']) ? $_POST['remove_images'] : [];
            if (!empty($remove_images)) {
                foreach ($remove_images as $remove_image) {
                    if (file_exists($remove_image)) {
                        unlink($remove_image); // Delete the file
                    }
                    // Remove the image from the existing list
                    if (($key = array_search($remove_image, $existing_images)) !== false) {
                        unset($existing_images[$key]);
                    }
                }
            }

            // Combine remaining existing images with new uploads
            $final_images = array_merge($existing_images, $image_paths);
            $image_paths_json = json_encode($final_images);

            // Update the announcement
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

    // Handle delete action
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $id = intval($_GET['id']);

        // Fetch the announcement details to retrieve image paths
        $query = "SELECT announcement_images FROM barangay_announcements WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Decode the JSON formatted image paths
            $image_paths = json_decode($result['announcement_images'], true);

            // Delete each image file if it exists
            if (!empty($image_paths)) {
                foreach ($image_paths as $image_path) {
                    if (file_exists($image_path)) {
                        unlink($image_path); // Delete the file
                    }
                }
            }

            // Delete the announcement from the database
            $sql = "DELETE FROM barangay_announcements WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Announcement and associated images deleted successfully!";
            } else {
                $_SESSION['error_message'] = "Error deleting the announcement. Please try again.";
            }
        } else {
            $_SESSION['error_message'] = "Announcement not found.";
        }

        // Close the statement and connection
        $stmt = null;
        $conn = null;

        // Redirect back to the announcements page
        header("Location: announcement.php");
        exit;
    }
    ?>

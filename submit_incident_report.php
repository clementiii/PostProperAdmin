<?php
require_once 'db.php';

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Increase PHP limits
ini_set('memory_limit', '256M');
ini_set('max_execution_time', 300);
ini_set('post_max_size', '50M');
ini_set('upload_max_filesize', '50M');

header('Content-Type: application/json');

$response = array();

try {
    // Log incoming data
    error_log("Received POST data: " . print_r($_POST, true));

    // Check if all required fields are present
    if (!isset($_POST['user_id']) || !isset($_POST['title']) || !isset($_POST['description'])) {
        throw new Exception('Missing required fields');
    }

    // Get user details for the name
    $stmt = $conn->prepare("SELECT firstName, lastName FROM user_accounts WHERE id = ?");
    $stmt->execute([$_POST['user_id']]);
    $user = $stmt->fetch();

    if (!$user) {
        throw new Exception('User not found');
    }

    $name = $user['firstName'] . ' ' . $user['lastName'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date_submitted = date('Y-m-d H:i:s');
    $status = 'pending';
    $uploadedImages = array();

    // Handle image uploads if present
    if (!empty($_POST['incident_picture'])) {
        try {
            // Decode the JSON array of images
            $imageArray = json_decode($_POST['incident_picture'], true);
            
            // Check if decode was successful and it's an array
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid image data format');
            }

            // Create directory if it doesn't exist
            $uploadDir = 'uploads/incident_reports/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Process each image in the array
            foreach ($imageArray as $base64Image) {
                // Remove the data URI header if present
                $base64Image = str_replace('data:image/jpeg;base64,', '', $base64Image);
                $base64Image = str_replace(' ', '+', $base64Image);
                
                // Generate unique filename for each image
                $timestamp = time();
                $randomString = bin2hex(random_bytes(8));
                $filename = $timestamp . '_' . $randomString . '.jpg';
                $filepath = $uploadDir . $filename;
                
                // Decode and save image
                $imageData = base64_decode($base64Image);
                if ($imageData === false) {
                    throw new Exception('Failed to decode base64 image');
                }

                if (file_put_contents($filepath, $imageData) === false) {
                    throw new Exception('Failed to save image file');
                }

                $uploadedImages[] = $filepath;
            }
        } catch (Exception $e) {
            error_log("Image processing error: " . $e->getMessage());
            throw new Exception('Failed to process images: ' . $e->getMessage());
        }
    }

    // Convert image array to JSON
    $incident_picture_json = json_encode($uploadedImages);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO incident_reports (name, title, description, incident_picture, date_submitted, status) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    
    if (!$stmt->execute([$name, $title, $description, $incident_picture_json, $date_submitted, $status])) {
        throw new Exception('Database insertion failed: ' . implode(", ", $stmt->errorInfo()));
    }

    $response['status'] = 'success';
    $response['message'] = 'Incident report submitted successfully';

} catch (Exception $e) {
    error_log("Error in submit_incident_report.php: " . $e->getMessage());
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
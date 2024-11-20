<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include database connection
include 'db.php';

try {
    // Query to get announcements
    $query = "SELECT * FROM barangay_announcements ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    $announcements = array();
    
    while ($row = $stmt->fetch()) {
        // Get the raw image data
        $raw_images = $row['announcement_images'];
        
        // Initialize processed images array
        $processedImages = array();
        
        // Create base URL
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $baseUrl = $protocol . $_SERVER['HTTP_HOST'] . '/PostProperAdmin/';
        
        // Try to decode JSON string
        $images = json_decode($raw_images, true);
        
        // If json_decode failed, try to parse the string manually
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Remove extra escapes and try again
            $cleaned_json = stripslashes($raw_images);
            $images = json_decode($cleaned_json, true);
            
            // If still failed, try to parse the string as a comma-separated list
            if (json_last_error() !== JSON_ERROR_NONE && !empty($raw_images)) {
                $images = explode(',', str_replace(['[', ']', '"'], '', $raw_images));
            }
        }
        
        // Process images if we have any
        if (!empty($images) && is_array($images)) {
            foreach ($images as $image) {
                if (!empty($image) && trim($image) !== '/') {
                    // Clean the image path
                    $image = str_replace('\\/', '/', $image);
                    $image = ltrim($image, '/');
                    $image = trim($image);
                    
                    // Skip if it's just a slash
                    if ($image === '/' || empty($image)) {
                        continue;
                    }
                    
                    // Make sure we have the uploads/announcements prefix
                    if (strpos($image, 'uploads/announcements') === false) {
                        $image = 'uploads/announcements/' . basename($image);
                    }
                    
                    // Construct full URL
                    $fullImageUrl = $baseUrl . $image;
                    $processedImages[] = $fullImageUrl;
                }
            }
        }
        
        // Format the announcement data
        $announcement = array(
            "id" => $row['id'],
            "announcement_title" => $row['announcement_title'],
            "description_text" => $row['description_text'],
            "announcement_images" => $processedImages,
            "created_at" => $row['created_at'],
            "posted_at" => $row['posted_at']
        );
        
        array_push($announcements, $announcement);
    }
    
    // Set response code - 200 OK and output the data
    http_response_code(200);
    echo json_encode($announcements);
    
} catch(PDOException $e) {
    // Set response code - 500 Internal server error
    http_response_code(500);
    echo json_encode(array("message" => "Error: " . $e->getMessage()));
}

// Close connection
$conn = null;
?>
<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $requestId = $_POST['requestId'];
        $uploadDir = 'uploads/';
        $validIdsDir = $uploadDir . 'valid_ids/';
        
        // Create directories if they don't exist
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        if (!is_dir($validIdsDir)) mkdir($validIdsDir, 0777, true);

        // Handle valid ID upload
        if (isset($_FILES['validId']) && $_FILES['validId']['error'] === UPLOAD_ERR_OK) {
            // Get file info
            $fileInfo = pathinfo($_FILES['validId']['name']);
            $extension = strtolower($fileInfo['extension']);
            
            // Validate file type
            $allowedTypes = ['jpg', 'jpeg', 'png'];
            if (!in_array($extension, $allowedTypes)) {
                throw new Exception('Invalid file type. Only JPG and PNG are allowed.');
            }

            // Generate unique filename
            $validIdName = time() . '_' . uniqid() . '.' . $extension;
            $validIdPath = 'uploads/valid_ids/' . $validIdName; // Database path
            $fullValidIdPath = __DIR__ . '/' . $validIdPath; // Full server path

            // Move uploaded file
            if (move_uploaded_file($_FILES['validId']['tmp_name'], $fullValidIdPath)) {
                // Update database
                $stmt = $conn->prepare("UPDATE document_requests SET 
                    valid_id = :validId,
                    Quantity = :quantity 
                    WHERE Id = :requestId");

                $stmt->bindParam(':validId', $validIdPath);
                $stmt->bindParam(':quantity', $_POST['quantity']);
                $stmt->bindParam(':requestId', $requestId);

                if ($stmt->execute()) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Requirements uploaded successfully',
                        'path' => $validIdPath
                    ]);
                } else {
                    throw new Exception('Failed to update database');
                }
            } else {
                throw new Exception('Failed to save uploaded file');
            }
        } else {
            throw new Exception('No file uploaded or upload error occurred');
        }

    } catch (Exception $e) {
        error_log("Upload error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Error uploading requirements: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}

// Log error details if any
if (isset($_FILES['validId']['error']) && $_FILES['validId']['error'] !== UPLOAD_ERR_OK) {
    $uploadErrors = [
        UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
    ];
    
    $errorMessage = isset($uploadErrors[$_FILES['validId']['error']]) 
        ? $uploadErrors[$_FILES['validId']['error']] 
        : 'Unknown upload error';
    
    error_log("File upload error: " . $errorMessage);
}
?>
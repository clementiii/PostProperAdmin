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

        $validIdFrontPath = '';
        $validIdBackPath = '';
        $errors = [];

        // Function to handle file upload
        function handleFileUpload($file, $prefix) {
            global $validIdsDir;
            
            if ($file['error'] === UPLOAD_ERR_OK) {
                $fileInfo = pathinfo($file['name']);
                $extension = strtolower($fileInfo['extension']);
                
                // Validate file type
                $allowedTypes = ['jpg', 'jpeg', 'png'];
                if (!in_array($extension, $allowedTypes)) {
                    throw new Exception("Invalid file type for {$prefix}. Only JPG and PNG are allowed.");
                }

                // Generate unique filename
                $fileName = time() . '_' . uniqid() . '_' . $prefix . '.' . $extension;
                $dbPath = 'uploads/valid_ids/' . $fileName;
                $fullPath = __DIR__ . '/' . $dbPath;

                if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
                    throw new Exception("Failed to save {$prefix} file");
                }

                return $dbPath;
            } else {
                throw new Exception("Error uploading {$prefix}: " . getUploadError($file['error']));
            }
        }

        // Handle front ID upload
        if (isset($_FILES['frontId'])) {
            $validIdFrontPath = handleFileUpload($_FILES['frontId'], 'front');
        } else {
            $errors[] = 'Front ID image is required';
        }

        // Handle back ID upload
        if (isset($_FILES['backId'])) {
            $validIdBackPath = handleFileUpload($_FILES['backId'], 'back');
        } else {
            $errors[] = 'Back ID image is required';
        }

        // If there are any errors, throw an exception
        if (!empty($errors)) {
            throw new Exception(implode(', ', $errors));
        }

        // Update database
        $stmt = $conn->prepare("UPDATE document_requests SET 
            valid_id_front = :frontId,
            valid_id_back = :backId,
            Quantity = :quantity 
            WHERE Id = :requestId");

        $stmt->bindParam(':frontId', $validIdFrontPath);
        $stmt->bindParam(':backId', $validIdBackPath);
        $stmt->bindParam(':quantity', $_POST['quantity']);
        $stmt->bindParam(':requestId', $requestId);

        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => 'Requirements uploaded successfully',
                'front_path' => $validIdFrontPath,
                'back_path' => $validIdBackPath
            ]);
        } else {
            throw new Exception('Failed to update database');
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

function getUploadError($errorCode) {
    $uploadErrors = [
        UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
    ];
    
    return isset($uploadErrors[$errorCode]) 
        ? $uploadErrors[$errorCode] 
        : 'Unknown upload error';
}
?>
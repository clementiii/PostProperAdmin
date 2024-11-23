<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $requestId = $_POST['requestId'];
        $uploadDir = 'uploads/';
        
        // Create directories if they don't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (!is_dir($uploadDir . 'valid_ids/')) {
            mkdir($uploadDir . 'valid_ids/', 0777, true);
        }

        // Handle valid ID upload
        if (isset($_FILES['validId'])) {
            $validIdName = time() . '_' . basename($_FILES['validId']['name']);
            $validIdPath = $uploadDir . 'valid_ids/' . $validIdName;
            
            if (move_uploaded_file($_FILES['validId']['tmp_name'], $validIdPath)) {
                // Update database with valid ID path
                $stmt = $conn->prepare("UPDATE document_requests SET valid_id = :validId, Quantity = :quantity WHERE Id = :requestId");
                $stmt->bindParam(':validId', $validIdPath);
                $stmt->bindParam(':quantity', $_POST['quantity']);
                $stmt->bindParam(':requestId', $requestId);
                $stmt->execute();

                echo json_encode([
                    'success' => true,
                    'message' => 'Requirements uploaded successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to upload file'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No file uploaded'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error uploading requirements: ' . $e->getMessage()
        ]);
    }
}
?>
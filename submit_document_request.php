<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get POST data and set defaults
        $documentType = $_POST['documentType'] ?? 'Barangay Clearance'; // Get documentType from POST
        $name = $_POST['name'] ?? '';
        $address = $_POST['address'] ?? '';
        $tin = $_POST['tin'] ?? '';
        $ctc = $_POST['ctc'] ?? '';
        $alias = $_POST['alias'] ?? '';
        $age = isset($_POST['age']) ? intval($_POST['age']) : 0;
        $birthday = $_POST['birthday'] ?? '';
        $lengthOfStay = isset($_POST['lengthOfStay']) ? intval($_POST['lengthOfStay']) : 0;
        $citizenship = $_POST['citizenship'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $civilStatus = $_POST['civilStatus'] ?? '';
        $purpose = $_POST['purpose'] ?? '';
        $status = 'Pending';
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        $dateRequested = date('Y-m-d');
        
        // Log received data for debugging
        error_log("Received document type: " . $documentType);
        
        $sql = "INSERT INTO document_requests (
            DocumentType, Name, Address, TIN_No, CTC_No, 
            Alias, Age, birthday, LengthOfStay, Citizenship, Gender, 
            CivilStatus, Purpose, Status, Quantity, DateRequested,
            valid_id, request_picture, rejection_reason
        ) VALUES (
            :documentType, :name, :address, :tin, :ctc,
            :alias, :age, :birthday, :lengthOfStay, :citizenship, :gender,
            :civilStatus, :purpose, :status, :quantity, :dateRequested,
            '', '', ''
        )";

        $stmt = $conn->prepare($sql);
        
        // Bind all parameters
        $stmt->bindParam(':documentType', $documentType);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':tin', $tin);
        $stmt->bindParam(':ctc', $ctc);
        $stmt->bindParam(':alias', $alias);
        $stmt->bindParam(':age', $age, PDO::PARAM_INT);
        $stmt->bindParam(':birthday', $birthday);
        $stmt->bindParam(':lengthOfStay', $lengthOfStay, PDO::PARAM_INT);
        $stmt->bindParam(':citizenship', $citizenship);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':civilStatus', $civilStatus);
        $stmt->bindParam(':purpose', $purpose);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':dateRequested', $dateRequested);

        if ($stmt->execute()) {
            $requestId = $conn->lastInsertId();
            echo json_encode([
                'success' => true,
                'message' => 'Document request submitted successfully',
                'requestId' => $requestId
            ]);
        } else {
            throw new Exception("Database error: " . implode(", ", $stmt->errorInfo()));
        }
    } catch (Exception $e) {
        error_log("Error in document request: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Error submitting request: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
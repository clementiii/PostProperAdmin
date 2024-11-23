<?php
header('Content-Type: application/json');
require_once 'db.php';

// Enable error logging
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// Log the raw request
error_log("Received request: " . file_get_contents('php://input'));
error_log("POST data: " . print_r($_POST, true));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Set default values
        $status = 'Pending';
        $dateRequested = date('Y-m-d');

        // Validate required fields
        $requiredFields = ['documentType', 'name', 'address'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }
        
        $sql = "INSERT INTO document_requests (
            DocumentType, Name, Address, TIN_No, CTC_No, 
            Alias, Age, LengthOfStay, Citizenship, Gender, 
            CivilStatus, Purpose, Status, Quantity, DateRequested,
            valid_id, request_picture, rejection_reason
        ) VALUES (
            :documentType, :name, :address, :tin, :ctc,
            :alias, :age, :lengthOfStay, :citizenship, :gender,
            :civilStatus, :purpose, :status, :quantity, :dateRequested,
            '', '', ''
        )";

        error_log("Preparing SQL: $sql");

        $stmt = $conn->prepare($sql);

        // Log the bound parameters
        $params = [
            ':documentType' => $_POST['documentType'],
            ':name' => $_POST['name'],
            ':address' => $_POST['address'],
            ':tin' => $_POST['tin'] ?? '',
            ':ctc' => $_POST['ctc'] ?? '',
            ':alias' => $_POST['alias'] ?? '',
            ':age' => intval($_POST['age'] ?? 0),
            ':lengthOfStay' => intval($_POST['lengthOfStay'] ?? 0),
            ':citizenship' => $_POST['citizenship'] ?? '',
            ':gender' => $_POST['gender'] ?? '',
            ':civilStatus' => $_POST['civilStatus'] ?? '',
            ':purpose' => $_POST['purpose'] ?? '',
            ':status' => $status,
            ':quantity' => intval($_POST['quantity'] ?? 1),
            ':dateRequested' => $dateRequested
        ];

        error_log("Parameters: " . print_r($params, true));
        
        // Bind parameters
        foreach ($params as $key => $value) {
            if (in_array($key, [':age', ':lengthOfStay', ':quantity'])) {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value, PDO::PARAM_STR);
            }
        }

        $result = $stmt->execute();
        if (!$result) {
            throw new Exception("Execute failed: " . implode(", ", $stmt->errorInfo()));
        }

        $requestId = $conn->lastInsertId();
        error_log("Successfully inserted document request with ID: $requestId");

        echo json_encode([
            'success' => true,
            'message' => 'Document request submitted successfully',
            'requestId' => $requestId
        ]);
    } catch (Exception $e) {
        error_log("Error in document request submission: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Error submitting document request: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
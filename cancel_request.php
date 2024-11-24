<?php

header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $requestId = isset($_POST['requestId']) ? intval($_POST['requestId']) : 0;
        
        if (!$requestId) {
            throw new Exception('Request ID is required');
        }
        
        $sql = "UPDATE document_requests SET Status = 'Cancelled' WHERE Id = :requestId AND Status = 'Pending'";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':requestId', $requestId, PDO::PARAM_INT);
        
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Request cancelled successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Unable to cancel request. It may already be processed.'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>
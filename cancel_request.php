<?php
header('Content-Type: application/json');
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = isset($_POST['requestId']) ? intval($_POST['requestId']) : 0;
    $reason = isset($_POST['reason']) ? $_POST['reason'] : '';

    if (!$requestId) {
        echo json_encode(['success' => false, 'message' => 'Request ID is required']);
        exit;
    }

    try {
        $sql = "UPDATE document_requests 
                SET Status = 'Cancelled', 
                    cancellation_reason = :reason 
                WHERE Id = :requestId";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':requestId', $requestId);
        $stmt->bindParam(':reason', $reason);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Request cancelled successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to cancel request']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
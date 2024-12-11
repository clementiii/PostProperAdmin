<?php
// Database connection
require_once 'db.php';

// Set timezone to Philippines
date_default_timezone_set('Asia/Manila');

try {
    // Get all approved documents that haven't been picked up
    $query = "SELECT Id, DateRequested, Status 
              FROM document_requests 
              WHERE Status = 'approved' 
              AND (pickup_status IS NULL OR pickup_status = 'pending')";
    
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get current time in Philippines
    $currentTime = new DateTime();

    foreach ($documents as $document) {
        // Convert request date to DateTime
        $requestDate = new DateTime($document['DateRequested']);
        
        // Calculate the difference
        $interval = $currentTime->diff($requestDate);
        $daysDifference = $interval->days;

        // If more than 3 days have passed
        if ($daysDifference > 3) {
            // Update status to OVERDUE
            $updateQuery = "UPDATE document_requests 
                          SET Status = 'OVERDUE' 
                          WHERE Id = :documentId";
            
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bindParam(':documentId', $document['Id']);
            $updateStmt->execute();

            // Log the status change
            $logQuery = "INSERT INTO status_change_logs 
                        (document_id, old_status, new_status, change_date) 
                        VALUES (:documentId, :oldStatus, 'OVERDUE', NOW())";
            
            $logStmt = $conn->prepare($logQuery);
            $logStmt->bindParam(':documentId', $document['Id']);
            $logStmt->bindParam(':oldStatus', $document['Status']);
            $logStmt->execute();
        }
    }

    echo "Document status check completed successfully.";
} catch (PDOException $e) {
    error_log("Error checking document status: " . $e->getMessage());
    echo "Error checking document status. Please check error logs.";
}

// Close the connection
$conn = null;
?>
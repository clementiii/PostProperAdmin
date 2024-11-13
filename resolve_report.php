<?php
session_start();
include 'db.php';

if (isset($_POST['report_id'])) {
    $report_id = $_POST['report_id'];

    try {
        // Update the report status to 'resolved'
        $sql = "UPDATE incident_reports SET status = 'resolved' WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $report_id, PDO::PARAM_INT);
        $stmt->execute();

        echo "Report resolved successfully.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid report ID.";
}

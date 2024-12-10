<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $userId = isset($_GET['userId']) ? intval($_GET['userId']) : 0;
        
        if (!$userId) {
            throw new Exception('User ID is required');
        }
        
        $sql = "SELECT i.*, ua.full_name as name 
                FROM incident_reports i 
                JOIN user_accounts ua ON i.name = ua.full_name 
                WHERE ua.id = :userId 
                ORDER BY date_submitted DESC";
                
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'reports' => $reports
        ]);
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
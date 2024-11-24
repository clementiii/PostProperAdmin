<?php
// get_user_requests.php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $userId = isset($_GET['userId']) ? intval($_GET['userId']) : 0;
        
        if (!$userId) {
            throw new Exception('User ID is required');
        }
        
        $sql = "SELECT * FROM document_requests WHERE userId = :userId ORDER BY DateRequested DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'requests' => $requests
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
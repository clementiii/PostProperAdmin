<?php
require_once 'db.php';

header('Content-Type: application/json');

$response = array();

try {
    if (!isset($_POST['user_id'])) {
        throw new Exception('User ID is required');
    }

    $userId = $_POST['user_id'];
    $currentTimestamp = date('Y-m-d H:i:s');

    // Update the user's last active timestamp
    $stmt = $conn->prepare("UPDATE user_accounts SET last_active = :timestamp WHERE id = :userId");
    $stmt->execute([
        ':timestamp' => $currentTimestamp,
        ':userId' => $userId
    ]);

    if ($stmt->rowCount() > 0) {
        $response['status'] = 'success';
        $response['message'] = 'Activity updated successfully';
    } else {
        throw new Exception('User not found');
    }

} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
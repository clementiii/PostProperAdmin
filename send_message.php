<?php
header('Content-Type: application/json');
include 'db.php';

// Function to safely log debug info
function logDebug($message, $data = null) {
    error_log($message . ($data ? ": " . print_r($data, true) : ""));
}

// Function to find user by full name - handles multiple word names
function findUserByFullName($conn, $fullName) {
    $fullName = trim(preg_replace('/\s+/', ' ', $fullName)); // Normalize spaces
    logDebug("Normalized full name", $fullName);

    try {
        $stmt = $conn->prepare("SELECT id FROM user_accounts WHERE CONCAT(firstName, ' ', lastName) = :fullName");
        $stmt->bindParam(':fullName', $fullName);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        logDebug("Exact match not found, trying LIKE query");

        $stmt = $conn->prepare("
            SELECT id, firstName, lastName,
                   CONCAT(firstName, ' ', lastName) as full_name
            FROM user_accounts 
            WHERE CONCAT(firstName, ' ', lastName) LIKE :fullNamePattern
        ");
        $pattern = '%' . $fullName . '%';
        $stmt->bindParam(':fullNamePattern', $pattern);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        logDebug("No user found for full name", $fullName);
        return null;
    } catch (PDOException $e) {
        logDebug("Database error in findUserByFullName", $e->getMessage());
        return null;
    }
}

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);

// Debug logs
logDebug("Received POST data", $_POST);
logDebug("Received JSON data", $input);

// Handle Android request
if ($input && isset($input['sender_id'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, message, is_admin) VALUES (:sender_id, :message, :is_admin)");
        $stmt->bindParam(':sender_id', $input['sender_id']);
        $stmt->bindParam(':message', $input['message']);
        $is_admin = 0;
        $stmt->bindParam(':is_admin', $is_admin);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to send message']);
        }
    } catch (PDOException $e) {
        logDebug("Error in Android message", $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
// Handle Web admin request
else if (isset($_POST['message']) && isset($_POST['recipient'])) {
    try {
        $user = findUserByFullName($conn, $_POST['recipient']);
        
        if (!$user) {
            logDebug("User not found", $_POST['recipient']);
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO messages (sender_id, message, is_admin) VALUES (:sender_id, :message, :is_admin)");
        $stmt->bindParam(':sender_id', $user['id']);
        $stmt->bindParam(':message', $_POST['message']);
        $is_admin = 1;
        $stmt->bindParam(':is_admin', $is_admin);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to send message']);
        }
    } catch (PDOException $e) {
        logDebug("Error in web message", $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    logDebug("Missing required fields");
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
}
?>
<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pps_barangay_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    
    $sql = "SELECT status FROM user_accounts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $response = array();
    
    if ($row = $result->fetch_assoc()) {
        $response['status'] = $row['status'];
        if ($row['status'] === 'verified') {
            $response['message'] = 'Your account has been verified!';
        } else if ($row['status'] === 'rejected') {
            $response['message'] = 'Your account has been rejected. Please contact support.';
        } else {
            $response['message'] = 'Your account is still pending verification.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'User not found';
    }
    
    $stmt->close();
    $conn->close();
    
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    $response = array(
        'status' => 'error',
        'message' => 'User ID not provided'
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
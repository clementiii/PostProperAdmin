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

if (isset($_POST['username']) && isset($_POST['password'])) {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Modified SQL to include status check
    $sql = "SELECT id, password, status FROM user_accounts WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $inputUsername, $inputPassword);
    $stmt->execute();
    $stmt->store_result();

    $response = array();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $storedPassword, $accountStatus);
        $stmt->fetch();
    
        if ($inputPassword == $storedPassword) {
            $response['status'] = 'success';
            $response['id'] = $userId;
            $response['accountStatus'] = $accountStatus;
            
            if ($accountStatus === 'pending') {
                $response['message'] = 'Your account is pending verification.';
            } else if ($accountStatus === 'rejected') {
                $response['message'] = 'Your account has been rejected.';
            }
        } else {
            $response['status'] = 'failure';
            $response['message'] = 'Invalid password';
        }
    } else {
        $response['status'] = 'failure';
        $response['message'] = 'Invalid username';
    }

    $stmt->close();
    $conn->close();
    
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    $response = array('status' => 'failure', 'message' => 'Username or password not provided.');
    echo json_encode($response);
}
?>
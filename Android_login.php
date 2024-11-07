<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = "";     // Replace with your MySQL password
$dbname = "pps_barangay_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the parameters are passed via POST request
if (isset($_POST['username']) && isset($_POST['password'])) {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Prepare SQL statement to fetch username and password
    $sql = "SELECT id, password FROM user_accounts WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $inputUsername, $inputPassword);
    $stmt->execute();
    $stmt->store_result();

    $response = array();

    if ($stmt->num_rows > 0) {
        // Bind the result to retrieve the id
        $stmt->bind_result($userId, $storedPassword);
        $stmt->fetch();

        // Check if the passwords match
        if ($inputPassword == $storedPassword) {
            $response['status'] = 'success';
            $response['id'] = $userId;
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
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // If username or password is not set in the POST request
    $response = array('status' => 'failure', 'message' => 'Username or password not provided.');
    echo json_encode($response);
}
?>

<?php
// Database connection parameters
$host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "pps_barangay_system";

// Create connection
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set response header to JSON
header('Content-Type: application/json');

// Check if all required fields are present
if (isset($_POST['firstName']) && isset($_POST['lastName']) && 
    isset($_POST['username']) && isset($_POST['password'])) {
    
    // Get and sanitize input data
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $age = isset($_POST['age']) ? intval($_POST['age']) : null;
    $birthday = isset($_POST['birthday']) ? $conn->real_escape_string($_POST['birthday']) : null;
    $houseNo = isset($_POST['adrHouseNo']) ? $conn->real_escape_string($_POST['adrHouseNo']) : null;
    $zone = isset($_POST['adrZone']) ? $conn->real_escape_string($_POST['adrZone']) : null;
    $street = isset($_POST['adrStreet']) ? $conn->real_escape_string($_POST['adrStreet']) : null;
    
    // Check if username already exists
    $check_query = "SELECT id FROM user_accounts WHERE username = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Username already exists'
        ]);
        exit();
    }
    
    // Prepare the INSERT statement
    $insert_query = "INSERT INTO user_accounts (firstName, lastName, username, password, 
                    age, birthday, adrHouseNo, adrZone, adrStreet) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssssissss", 
        $firstName, $lastName, $username, $password, 
        $age, $birthday, $houseNo, $zone, $street
    );
    
    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Registration successful'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Registration failed: ' . $stmt->error
        ]);
    }
    
    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Missing required fields'
    ]);
}

$conn->close();
?>
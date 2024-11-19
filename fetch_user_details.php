<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pps_barangay_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the 'id' parameter is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare SQL statement to fetch user details
    $sql = "SELECT firstName, lastName, username, age, gender, adrHouseNo, adrZone, adrStreet, birthday, password, user_profile_picture 
            FROM user_accounts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        // Fetch user details
        $row = $result->fetch_assoc();
        // Respond with user details in JSON format
        echo json_encode(array_merge(['status' => 'success'], $row));
    } else {
        echo json_encode(['status' => 'failure', 'message' => 'User not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'failure', 'message' => 'ID not provided']);
}

$conn->close();
?>

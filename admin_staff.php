<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php"); // Redirect to the login page if not logged in
    exit;
}

include 'db.php'; // Make sure this file connects to your `pps_barangay_system` database

// Query the database to get all admin users
try {
    $sql = "SELECT id, name FROM admin_accounts"; // Adjust based on your table structure
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all admins as an associative array
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Staff</title>
    
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/AdminStaff.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    
<?php 
    $pageTitle = "Admin Staff";
    include 'header.php';
    include 'sidebar.php'; 
?>

<div class="main-content">
    <div class="container">
        <table class="table table-bordered mt-4">
            <thead class="table-light">
                <tr>
                    <th scope="col">Admin Name</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($admins)) {
                    foreach ($admins as $admin) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($admin['name']) . "</td>"; // Escape name to prevent XSS
                        echo "<td class='text-center'>";
                        // Link to the profile page and pass the admin's ID as a parameter for editing
                        echo "<a href='admin_profile.php?id=" . $admin['id'] . "' class='btn btn-primary'>Edit</a>";
                        // Add Delete button
                        echo "<a href='delete_admin.php?id=" . $admin['id'] . "' class='btn btn-danger ms-2' onclick='return confirm(\"Are you sure you want to delete this admin?\");'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No admin users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Post Proper Southside Barangay Information System</title>
    <link rel="stylesheet" href="css/DashboardStyle.css">
</head>
<body>

<?php include 'sidebar.php'; ?> 

<div class="main-content">
    <!-- Header -->
    <div class="header">
        <h1>Welcome, Admin</h1>
        <div class="profile">
            <span>J</span>
            <img src="path-to-profile-image.png" alt="Profile Picture">
        </div>
    </div>

    <!-- Dashboard boxes -->
    <div class="dashboard">
        <div class="box">
            <h3>Registered Residents</h3>
        </div>
        <div class="box">
            <h3>Document Requests</h3>
        </div>
        <div class="box">
            <h3>Incident Reports</h3>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-section">
        <table>
            <thead>
                <tr>
                    <th>Column 1</th>
                    <th>Column 2</th>
                    <th>Column 3</th>
                    <th>Column 4</th>
                    <th>Column 5</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Column 1</td>
                    <td>Column 2</td>
                    <td>Column 3</td>
                    <td>Column 4</td>
                    <td>Column 5</td>
                </tr>
                <tr>
                    <td>Column 1</td>
                    <td>Column 2</td>
                    <td>Column 3</td>
                    <td>Column 4</td>
                    <td>Column 5</td>
                </tr>
                <tr>
                    <td>Column 1</td>
                    <td>Column 2</td>
                    <td>Column 3</td>
                    <td>Column 4</td>
                    <td>Column 5</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

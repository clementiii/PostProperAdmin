<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php"); // Redirect to the login page if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="css/Users.css?v=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<?php 
    $pageTitle = "Resident Users";
    include 'header.php';
    ?>
<?php include 'sidebar.php'; ?> 
<div class="main-content">
    <div class="container mt-4">
            <!-- Scrollable Transactions Table -->
            <div class="card">
        <div class="table-container">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Address</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- PHP to fetch data -->
                    <?php // foreach ($transactions as $transaction): ?>
                    <tr>
                        <td>Kiyosaki</td>
                        <td>Roberto</td>
                        <td>123 Swingfire St.</td>
                        <td>45</td>
                        <td>Male</td>
                        <td>10/12/1975</td>
                        <td>
                            <a href="#" class="btn btn-edit">Edit</a>
                            <a href="#" class="btn btn-delete">Delete</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Reyes</td>
                        <td>Maria</td>
                        <td>456 Fox St.</td>
                        <td>32</td>
                        <td>Female</td>
                        <td>07/24/1992</td>
                        <td>
                            <a href="#" class="btn btn-edit">Edit</a>
                            <a href="#" class="btn btn-delete">Delete</a>
                        </td>
                    </tr>
                        <!-- <?php // endforeach; ?> -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</div>

</body>
</html>
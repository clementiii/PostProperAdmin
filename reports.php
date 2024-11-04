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
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .action-button {
            width: 7vw;
            height: 4vh;
            background-color: #61009F !important; /* Overrides Bootstrap styles */
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none; /* Removes border for a cleaner look */
            border-radius: 8px; /* Rounded corners */
            text-decoration: none; /* Removes underline from link */
        }
        .table td, .table th {
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>
<body>
<?php 
    $pageTitle = "Incident Report and Monitoring";
    include 'header.php';
?>
<?php include 'sidebar.php'; ?>

<div class="main-content p-4">
    <div class="container">
        <table class="table table-striped table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date Submitted</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Robert Youngstown</td>
                    <td>Noise Disturbance</td>
                    <td>Maingay pa dito banda sa Sampaguita St...</td>
                    <td>05/17/204</td>
                    <td>Resolved</td>
                    <td><a href="report_verify.php" class="action-button">View</a></td>
                </tr>
                <tr>
                    <td>Pamela Padilla</td>
                    <td>Noise Disturbance</td>
                    <td>Maingay pa dito banda sa Sampaguita St...</td>
                    <td>10/17/204</td>
                    <td>Pending</td>
                    <td><a href="report_verify.php" class="action-button">View</a></td>
                </tr>
                <!-- Additional rows as needed -->
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

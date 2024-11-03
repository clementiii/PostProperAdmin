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
    <title>Documents</title>
    <link rel="stylesheet" href="css/Documents.css">
</head>
<body>
<?php 
    $pageTitle = "Documents";
    include 'header.php';
    ?>
<?php include 'sidebar.php'; ?> 
    <div class="main-content">
        <!-- Scrollable Transactions Table -->
        <div class="card">
            <div class="card-header">
                Recent Document Requests
            </div>
            <div class="table-container">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Name</th>
                            <th>Document Type</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Date Requested</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- PHP to fetch data -->
                        <?php // foreach ($transactions as $transaction): ?>
                        <tr>
                            <td>TXN-20230927</td>
                            <td>Robert Youngstown</td>
                            <td>Barangay Clearance</td>
                            <td>2</td>
                            <td>100.00</td>
                            <td>10/12/2024</td>
                            <td><a href="#" class="btn btn-primary btn-sm" style="background-color: #61009F; color: white;">View</a></td>
                        </tr>
                        <tr>
                            <td>TXN-20230928</td>
                            <td>Angelica Santos</td>
                            <td>Barangay Certificate</td>
                            <td>2</td>
                            <td>100.00</td>
                            <td>10/11/2024</td>
                            <td><a href="#" class="btn btn-primary btn-sm" style="background-color: #61009F; color: white;">View</a></td>
                        </tr>
                        <tr>
                            <td>TXN-20230929</td>
                            <td>Maria Gonzales</td>
                            <td>Certificate of Indigency</td>
                            <td>1</td>
                            <td>60.00</td>
                            <td>10/10/2024</td>
                            <td><a href="#" class="btn btn-primary btn-sm" style="background-color: #61009F; color: white;">View</a></td>
                        </tr>
                        <!-- <?php // endforeach; ?> -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>                        
</body>
</html>
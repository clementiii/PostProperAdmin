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
    <!-- Custom CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/Documents.css">
    <style>
        /* Table and button styling */
        .table td, .table th {
            text-align: center; /* Centers text in table cells */
            vertical-align: middle; /* Aligns text in the middle vertically */
        }
        
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
    </style>
</head>
<body>
<?php 
    $pageTitle = "Documents";
    include 'header.php';
?>
<?php include 'sidebar.php'; ?> 
    <div class="main-content">
        <!-- Summary Cards -->
        <div class="row text-center mb-4">
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card text-white bg-primary mb-3 custom-card">
                    <div class="card-body">
                        <h5 class="card-title">Total Request</h5>
                        <h3 class="card-text">555</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card text-white bg-warning mb-3 custom-card">
                    <div class="card-body">
                        <h5 class="card-title">Pending</h5>
                        <h3 class="card-text">125</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card text-white bg-success mb-3 custom-card">
                    <div class="card-body">
                        <h5 class="card-title">Approved</h5>
                        <h3 class="card-text">67</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card text-white bg-danger mb-3 custom-card">
                    <div class="card-body">
                        <h5 class="card-title">Rejected</h5>
                        <h3 class="card-text">67</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scrollable Transactions Table -->
        <div>
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
                        <tr>
                            <td>TXN-20230927</td>
                            <td>Robert Youngstown</td>
                            <td>Barangay Clearance</td>
                            <td>2</td>
                            <td>100.00</td>
                            <td>10/12/2024</td>
                            <td><a href="document_verify.php" class="action-button">View</a></td>
                        </tr>
                        <tr>
                            <td>TXN-20230928</td>
                            <td>Angelica Santos</td>
                            <td>Barangay Certificate</td>
                            <td>2</td>
                            <td>100.00</td>
                            <td>10/11/2024</td>
                            <td><a href="document_verify.php" class="action-button">View</a></td>
                        </tr>
                        <tr>
                            <td>TXN-20230929</td>
                            <td>Maria Gonzales</td>
                            <td>Certificate of Indigency</td>
                            <td>1</td>
                            <td>60.00</td>
                            <td>10/10/2024</td>
                            <td><a href="document_verify.php" class="action-button">View</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>         

<script>
document.addEventListener("DOMContentLoaded", function () {
    const table = document.querySelector(".table");
    const headers = table.querySelectorAll("th");
    const rows = Array.from(table.querySelectorAll("tbody tr"));

    headers.forEach((header, index) => {
        header.addEventListener("click", () => {
            const sortedRows = rows.sort((a, b) => {
                const cellA = a.cells[index].innerText.toLowerCase();
                const cellB = b.cells[index].innerText.toLowerCase();
                
                if (cellA < cellB) return -1;
                if (cellA > cellB) return 1;
                return 0;
            });
            
            // Append sorted rows back to the table
            const tbody = table.querySelector("tbody");
            sortedRows.forEach(row => tbody.appendChild(row));
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

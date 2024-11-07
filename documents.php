<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php"); // Redirect to the login page if not logged in
    exit;
}

$price = 100.00; // Default price for document requests
include 'db.php'; // Include the database connection

// Query to get document requests from the database
$query = "SELECT Id, Name, DocumentType, Quantity, $price*Quantity AS Price, DateRequested FROM document_requests";
$stmt = $conn->prepare($query);
$stmt->execute();
$documentRequests = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows
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
            text-align: center;
            vertical-align: middle;
        }
        .action-button {
            width: 7vw;
            height: 4vh;
            background-color: #61009F !important; 
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 8px;
            text-decoration: none; 
        }
        .card .card-text {
            position: relative;
            display: inline-block; 
            width: 14vw; 
            height: 7vh; 
            background-color: rgba(255, 255, 255, 0.1) !important; 
            border-radius: 0.5em !important;
            text-shadow: 0.06em 0.06em 0.12em rgba(0, 0, 0, 0.2) !important; 
            font-weight: bold !important; 
            color: inherit !important; 
            display: flex;
            align-items: center;
            justify-content: center;
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
    <div class="row text-center mb-4">
        <!-- Summary Cards -->
        <!-- Keep your existing summary card code here -->
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
                <?php
                // Check if there are results
                if (!empty($documentRequests)) {
                    foreach ($documentRequests as $row) {
                        echo "<tr>";
                        echo "<td>TXN-" . htmlspecialchars($row['Id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['DocumentType']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Quantity']) . "</td>";
                        echo "<td>₱ " . htmlspecialchars($row['Price']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['DateRequested']) . "</td>";
                        echo '<td><a href="document_verify.php" class="action-button">View</a></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No document requests found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
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
            
            const tbody = table.querySelector("tbody");
            sortedRows.forEach(row => tbody.appendChild(row));
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn = null;
?>

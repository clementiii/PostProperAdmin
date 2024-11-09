<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php"); // Redirect to the login page if not logged in
    exit;
}

$price = 100.00; // Default price for document requests
include 'db.php'; // Include the database connection

// Query to get document requests and calculate total requests
$query = "SELECT Id, Name, DocumentType, Quantity, $price * Quantity AS Price, DateRequested, Status FROM document_requests";
$stmt = $conn->prepare($query);
$stmt->execute();
$documentRequests = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows

// Get counts for the summary cards
$totalRequestQuery = "SELECT COUNT(*) AS total FROM document_requests";
$pendingCountQuery = "SELECT COUNT(*) AS pending FROM document_requests WHERE LOWER(Status) = 'pending'";
$approvedCountQuery = "SELECT COUNT(*) AS approved FROM document_requests WHERE LOWER(Status) = 'approved'";
$rejectedCountQuery = "SELECT COUNT(*) AS rejected FROM document_requests WHERE LOWER(Status) = 'rejected'";

$totalRequest = $conn->query($totalRequestQuery)->fetch(PDO::FETCH_ASSOC)['total'];
$pendingCount = $conn->query($pendingCountQuery)->fetch(PDO::FETCH_ASSOC)['pending'];
$approvedCount = $conn->query($approvedCountQuery)->fetch(PDO::FETCH_ASSOC)['approved'];
$rejectedCount = $conn->query($rejectedCountQuery)->fetch(PDO::FETCH_ASSOC)['rejected'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Requests</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/Documents.css">
</head>
<body>
<?php 
    $pageTitle = "Document Requests";
    include 'header.php';
    include 'sidebar.php'; 
?> 

<div class="main-content">
    <!-- Summary Cards for Document Request Statuses -->
    <div class="statistic-container row text-center mb-4">
        <div class="col">
            <div class="card" style="background: linear-gradient(180deg, #3498DB, #5DADE2); width: 250px; height: 150px; color: white;">
                <h2 class="card-title">Total Request</h2>
                <div class="card-text" style="background: rgba(255, 255, 255, 0.1); width: 100%; height: 50%;">
                    <?php echo $totalRequest; ?>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card" style="background: linear-gradient(180deg, #D68910, #F5B041); width: 250px; height: 150px; color: white;">
                <h2 class="card-title">Pending</h2>
                <div class="card-text" style="background: rgba(255, 255, 255, 0.1); width: 100%; height: 50%;">
                    <?php echo $pendingCount; ?>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card" style="background: linear-gradient(180deg, #229954, #27AE60); width: 250px; height: 150px; color: white;">
                <h2 class="card-title">Approved</h2>
                <div class="card-text" style="background: rgba(255, 255, 255, 0.1); width: 100%; height: 50%;">
                    <?php echo $approvedCount; ?>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card" style="background: linear-gradient(180deg, #A93226, #E74C3C); width: 250px; height: 150px; color: white;">
                <h2 class="card-title">Rejected</h2>
                <div class="card-text" style="background: rgba(255, 255, 255, 0.1); width: 100%; height: 50%;">
                    <?php echo $rejectedCount; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Requests Table -->
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
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($documentRequests)) {
                    foreach ($documentRequests as $row) {
                        echo "<tr>";
                        echo "<td>TXN-" . htmlspecialchars($row['Id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['DocumentType']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Quantity']) . "</td>";
                        echo "<td>₱ " . htmlspecialchars($row['Price']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['DateRequested']) . "</td>";
                        echo "<td>" . ucfirst(htmlspecialchars(strtolower($row['Status']))) . "</td>";
                        echo '<td><a href="document_verify.php" class="action-button">View</a></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No document requests found.</td></tr>";
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

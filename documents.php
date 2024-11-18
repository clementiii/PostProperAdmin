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

<div class="main-content px-4">
    <!-- Summary Cards for Document Request Statuses -->
    <div class="statistic-container row text-center ">
        <div class="col">
            <div class="card card-request">
                <h2 class="card-title">Total Request</h2>
                <div class="card-text" >
                    <?php echo $totalRequest; ?>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card card-pending">
                <h2 class="card-title">Pending</h2>
                <div class="card-text" >
                    <?php echo $pendingCount; ?>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card card-approved">
                <h2 class="card-title">Approved</h2>
                <div class="card-text" >
                    <?php echo $approvedCount; ?>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card card-rejected">
                <h2 class="card-title">Rejected</h2>
                <div class="card-text">
                    <?php echo $rejectedCount; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Requests Table -->
    <div class="table-responsive" >
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th data-sort="number">Transaction ID</th>
                    <th data-sort="string">Name</th>
                    <th data-sort="string">Document Type</th>
                    <th data-sort="number">Quantity</th>
                    <th data-sort="number">Price</th>
                    <th data-sort="date">Date Requested</th>
                    <th data-sort="status">Status</th>
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
                        echo '<td><a href="document_verify.php?id=' . htmlspecialchars($row['Id']) . '" class="action-button" >View</a></td>';
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
    const headers = table.querySelectorAll("th[data-sort]");
    const rows = Array.from(table.querySelectorAll("tbody tr"));
    let sortDirection = {};

    headers.forEach((header, index) => {
        const type = header.getAttribute("data-sort");
        sortDirection[type] = 1; // Initialize sorting direction (1 for ascending, -1 for descending)

        header.addEventListener("click", () => {
            let sortedRows;

            // Three-way sorting for "status" column
            if (type === "status") {
                const statusOrderAsc = { "pending": 1, "approved": 2, "rejected": 3 };
                const statusOrderDesc = { "rejected": 1, "approved": 2, "pending": 3 };

                const currentOrder = sortDirection[type] === 1 ? statusOrderAsc : statusOrderDesc;
                sortedRows = rows.sort((a, b) => currentOrder[a.cells[index].innerText.toLowerCase()] - currentOrder[b.cells[index].innerText.toLowerCase()]);
                
                // Toggle sort direction for "status" on each click
                sortDirection[type] *= -1; 
            } else if (type === "number") {
                // Numeric sorting (e.g., Quantity, Price)
                sortedRows = rows.sort((a, b) => (parseFloat(a.cells[index].innerText.replace(/[^0-9.-]+/g,"")) - parseFloat(b.cells[index].innerText.replace(/[^0-9.-]+/g,""))) * sortDirection[type]);
            } else if (type === "string") {
                // Alphabetical sorting (e.g., Name, Document Type)
                sortedRows = rows.sort((a, b) => a.cells[index].innerText.localeCompare(b.cells[index].innerText) * sortDirection[type]);
            } else if (type === "date") {
                // Date sorting (e.g., Date Requested)
                sortedRows = rows.sort((a, b) => (new Date(b.cells[index].innerText) - new Date(a.cells[index].innerText)) * sortDirection[type]);
            }

            // Update table with sorted rows
            const tbody = table.querySelector("tbody");
            tbody.innerHTML = ""; // Clear current rows
            sortedRows.forEach(row => tbody.appendChild(row));

            // Reset other columns' sort direction if not "status"
            if (type !== "status") sortDirection[type] *= -1; 
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

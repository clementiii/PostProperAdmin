<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php"); // Redirect to the login page if not logged in
    exit;
}
include 'db.php';

// Function to calculate price based on document type
function calculatePrice($documentType, $quantity) {
    switch($documentType) {
        case 'Barangay Clearance':
        case 'Barangay Certification':
        case 'Certificate of Indigency':
            return "₱" . number_format(50.00 * $quantity, 2);
        case 'Cedula':
            return 'Depends on the income';
        default:
            return 'Price not set';
    }
}

// Query to get document requests with only existing columns
$query = "SELECT Id, Name, DocumentType, Quantity, birthday, DateRequested, Status, cancellation_reason FROM document_requests";
$stmt = $conn->prepare($query);
$stmt->execute();
$documentRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get counts for summary cards
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/png" href="assets/Southside.png">
</head>
<body>
<?php 
    $pageTitle = "Document Requests";
    include 'header.php';
    include 'sidebar.php'; 
?> 

<div class="main-content px-4">
    <!-- Summary Cards for Document Request Statuses -->
    <div class="statistic-container row text-center">
        <div class="col">
            <div class="card card-request">
                <h2 class="card-title">Total Request</h2>
                <div class="card-text">
                    <?php echo $totalRequest; ?>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card card-pending">
                <h2 class="card-title">Pending</h2>
                <div class="card-text">
                    <?php echo $pendingCount; ?>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card card-approved">
                <h2 class="card-title">Approved</h2>
                <div class="card-text">
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
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th data-sort="number">Transaction ID</th>
                    <th data-sort="string">Name</th>
                    <th data-sort="string">Document Type</th>
                    <th data-sort="number">Quantity</th>
                    <th data-sort="string">Price</th>
                    <th data-sort="date">Date Requested</th>
                    <th data-sort="status">Status</th>
                    <th>Details</th>
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
                        echo "<td>" . calculatePrice($row['DocumentType'], $row['Quantity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['DateRequested']) . "</td>";
                        
                        // Status with color coding and badges
                        $statusBadgeClass = '';
                        switch(strtolower($row['Status'])) {
                            case 'pending':
                                $statusBadgeClass = 'badge bg-warning';
                                break;
                            case 'approved':
                                $statusBadgeClass = 'badge bg-success';
                                break;
                            case 'rejected':
                                $statusBadgeClass = 'badge bg-danger';
                                break;
                            case 'cancelled':
                                $statusBadgeClass = 'badge bg-secondary';
                                break;
                        }
                        echo "<td><span class='{$statusBadgeClass}'>" . ucfirst(htmlspecialchars(strtolower($row['Status']))) . "</span></td>";

                        // Details column with modal trigger for cancelled requests
                        echo "<td>";
                        if (strtolower($row['Status']) === 'cancelled' && !empty($row['cancellation_reason'])) {
                            echo '<button class="btn btn-info btn-sm" onclick="showCancellationReason(\'' . 
                                 htmlspecialchars($row['cancellation_reason']) . 
                                 '\')"><i class="fas fa-info-circle"></i> Reason</button>';
                        } else {
                            echo "-";
                        }
                        echo "</td>";

                        // Action column
                        if (strtolower($row['Status']) === 'rejected') {
                            echo '<td><button class="action-button button-rejected" disabled>Rejected</button></td>';
                        } elseif (strtolower($row['Status']) === 'approved') {
                            echo '<td><button class="action-button button-approved" disabled>Approved</button></td>';
                        } elseif (strtolower($row['Status']) === 'cancelled') {
                            echo '<td><button class="action-button button-cancelled" disabled>Cancelled</button></td>';
                        } else {
                            echo '<td><a href="document_verify.php?id=' . htmlspecialchars($row['Id']) . '" class="action-button">View</a></td>';
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No document requests found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Cancellation Reason Modal -->
<div class="modal" id="cancellationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancellation Reason</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="cancellationReason" class="mb-0"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Table sorting functionality
    const table = document.querySelector(".table");
    const headers = table.querySelectorAll("th[data-sort]");
    const rows = Array.from(table.querySelectorAll("tbody tr"));
    let sortDirection = {};

    headers.forEach((header, index) => {
        const type = header.getAttribute("data-sort");
        sortDirection[type] = 1;

        header.addEventListener("click", () => {
            let sortedRows;

            if (type === "status") {
                const statusOrderAsc = { "pending": 1, "approved": 2, "rejected": 3, "cancelled": 4 };
                const statusOrderDesc = { "cancelled": 1, "rejected": 2, "approved": 3, "pending": 4 };
                const currentOrder = sortDirection[type] === 1 ? statusOrderAsc : statusOrderDesc;
                sortedRows = rows.sort((a, b) => currentOrder[a.cells[index].innerText.toLowerCase()] - currentOrder[b.cells[index].innerText.toLowerCase()]);
                sortDirection[type] *= -1;
            } else if (type === "number") {
                sortedRows = rows.sort((a, b) => (parseFloat(a.cells[index].innerText.replace(/[^0-9.-]+/g,"")) - parseFloat(b.cells[index].innerText.replace(/[^0-9.-]+/g,""))) * sortDirection[type]);
                sortDirection[type] *= -1;
            } else if (type === "string") {
                sortedRows = rows.sort((a, b) => a.cells[index].innerText.localeCompare(b.cells[index].innerText) * sortDirection[type]);
                sortDirection[type] *= -1;
            } else if (type === "date") {
                sortedRows = rows.sort((a, b) => (new Date(b.cells[index].innerText) - new Date(a.cells[index].innerText)) * sortDirection[type]);
                sortDirection[type] *= -1;
            }

            const tbody = table.querySelector("tbody");
            tbody.innerHTML = "";
            sortedRows.forEach(row => tbody.appendChild(row));
        });
    });

    // Modal functionality
    const modal = document.getElementById('cancellationModal');
    const modalInstance = new bootstrap.Modal(modal);

    window.showCancellationReason = function(reason) {
        document.getElementById('cancellationReason').textContent = reason;
        modalInstance.show();
    }

    modal.addEventListener('hidden.bs.modal', function () {
        document.getElementById('cancellationReason').textContent = '';
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn = null;
?>
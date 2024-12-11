<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php");
    exit;
}
include 'db.php';

// Set timezone to Philippines
date_default_timezone_set('Asia/Manila');

// Function to check and update overdue documents
function checkOverdueDocuments($conn) {
    try {
        // Get all approved documents that haven't been picked up
        // Modified to include date_approved instead of DateRequested
        $query = "SELECT Id, date_approved, Status 
                  FROM document_requests 
                  WHERE Status = 'approved' 
                  AND (pickup_status IS NULL OR pickup_status = 'pending')
                  AND date_approved IS NOT NULL";
        
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get current time in Philippines
        date_default_timezone_set('Asia/Manila');
        $currentTime = new DateTime();

        foreach ($documents as $document) {
            // Convert approval date to DateTime
            $approvalDate = new DateTime($document['date_approved']);
            
            // Calculate the difference
            $interval = $currentTime->diff($approvalDate);
            $daysDifference = $interval->days;

            // If more than 3 days have passed since approval
            if ($daysDifference > 3) {
                // Update status to OVERDUE
                $updateQuery = "UPDATE document_requests 
                              SET Status = 'OVERDUE' 
                              WHERE Id = :documentId";
                
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bindParam(':documentId', $document['Id']);
                $updateStmt->execute();

                // Log the status change
                $logQuery = "INSERT INTO status_change_logs 
                            (document_id, old_status, new_status, change_date, remarks) 
                            VALUES (:documentId, :oldStatus, 'OVERDUE', NOW(), 
                            'Document not picked up within 3 days of approval')";
                
                $logStmt = $conn->prepare($logQuery);
                $logStmt->bindParam(':documentId', $document['Id']);
                $logStmt->bindParam(':oldStatus', $document['Status']);
                $logStmt->execute();
            }
        }
    } catch (PDOException $e) {
        error_log("Error checking document status: " . $e->getMessage());
    }
}

// Check for overdue documents on page load
checkOverdueDocuments($conn);

// Handle AJAX requests for pickup status updates
if (isset($_POST['action']) && $_POST['action'] === 'updatePickupStatus') {
    $requestId = $_POST['requestId'];
    $newStatus = $_POST['newStatus'];
    
    try {
        $stmt = $conn->prepare("CALL update_pickup_status(?, ?)");
        $stmt->execute([$requestId, $newStatus]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// Query to get document requests including date_approved
$query = "SELECT Id, Name, DocumentType, DateRequested, date_approved, Status, cancellation_reason, pickup_status 
          FROM document_requests";
$stmt = $conn->prepare($query);
$stmt->execute();
$documentRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get counts for summary cards
$totalRequestQuery = "SELECT COUNT(*) AS total FROM document_requests";
$pendingCountQuery = "SELECT COUNT(*) AS pending FROM document_requests WHERE LOWER(Status) = 'pending'";
$approvedCountQuery = "SELECT COUNT(*) AS approved FROM document_requests WHERE LOWER(Status) = 'approved'";
$rejectedCountQuery = "SELECT COUNT(*) AS rejected FROM document_requests WHERE LOWER(Status) = 'rejected'";
$overdueCountQuery = "SELECT COUNT(*) AS overdue FROM document_requests WHERE LOWER(Status) = 'overdue'";

$totalRequest = $conn->query($totalRequestQuery)->fetch(PDO::FETCH_ASSOC)['total'];
$pendingCount = $conn->query($pendingCountQuery)->fetch(PDO::FETCH_ASSOC)['pending'];
$approvedCount = $conn->query($approvedCountQuery)->fetch(PDO::FETCH_ASSOC)['approved'];
$rejectedCount = $conn->query($rejectedCountQuery)->fetch(PDO::FETCH_ASSOC)['rejected'];
$overdueCount = $conn->query($overdueCountQuery)->fetch(PDO::FETCH_ASSOC)['overdue'];
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
                <div class="card-text"><?php echo $totalRequest; ?></div>
            </div>
        </div>
        <div class="col">
            <div class="card card-pending">
                <h2 class="card-title">Pending</h2>
                <div class="card-text"><?php echo $pendingCount; ?></div>
            </div>
        </div>
        <div class="col">
            <div class="card card-approved">
                <h2 class="card-title">Approved</h2>
                <div class="card-text"><?php echo $approvedCount; ?></div>
            </div>
        </div>
        <div class="col">
            <div class="card card-rejected">
                <h2 class="card-title">Rejected</h2>
                <div class="card-text"><?php echo $rejectedCount; ?></div>
            </div>
        </div>
        <div class="col">
            <div class="card card-overdue">
                <h2 class="card-title">Overdue</h2>
                <div class="card-text"><?php echo $overdueCount; ?></div>
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
                    <!-- <th data-sort="number">Quantity</th> -->
                    <!-- <th data-sort="string">Price</th> -->
                    <th data-sort="date">Date Requested</th>
                    <th data-sort="date">Date Approved</th>
                    <th data-sort="status">Status</th>
                    <th>Details</th>
                    <th>Action</th>
                    <th data-sort="string">Pickup Status</th>
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
                        // echo "<td>" . htmlspecialchars($row['Quantity']) . "</td>";
                        // echo "<td>" . calculatePrice($row['DocumentType'], $row['Quantity']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['DateRequested']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['date_approved'] ?? '-') . "</td>";
                        
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
                            case 'overdue':
                                $statusBadgeClass = 'badge bg-danger';
                                break;
                        }
                        echo "<td><span class='{$statusBadgeClass}'>" . ucfirst(htmlspecialchars(strtolower($row['Status']))) . "</span></td>";

                        // Details column
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
                        } elseif (strtolower($row['Status']) === 'overdue') {
                            echo '<td><button class="action-button button-overdue" disabled>Overdue</button></td>';
                        } else {
                            echo '<td><a href="document_verify.php?id=' . htmlspecialchars($row['Id']) . '" class="action-button">View</a></td>';
                        }

                        // Pickup Status column
                        echo "<td>";
                        if (strtolower($row['Status']) === 'approved') {
                            $pickupStatus = $row['pickup_status'] ?? 'pending';
                            $isPickedUp = $pickupStatus === 'picked_up';
                            echo '<div class="form-check form-switch">
                                    <input class="form-check-input pickup-toggle" type="checkbox" 
                                           data-request-id="' . $row['Id'] . '" 
                                           ' . ($isPickedUp ? 'checked' : '') . '>
                                    <label class="form-check-label">' . 
                                    ($isPickedUp ? 'Picked Up' : 'Not Picked Up') . 
                                    '</label>
                                  </div>';
                        } else {
                            echo '<span class="text-muted">N/A</span>';
                        }
                        echo "</td>";
                        
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/documents.js"></script>
</body>
</html>

<?php
$conn = null;
?>
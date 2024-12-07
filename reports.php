<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php");
    exit;
}

try {
    // Query for summary statistics
    $sqlTotal = "SELECT COUNT(*) AS count FROM incident_reports";
    $stmtTotal = $conn->prepare($sqlTotal);
    $stmtTotal->execute();
    $totalReports = $stmtTotal->fetch(PDO::FETCH_ASSOC)['count'];

    $sqlPending = "SELECT COUNT(*) AS count FROM incident_reports WHERE UPPER(status) = 'PENDING'";
    $stmtPending = $conn->prepare($sqlPending);
    $stmtPending->execute();
    $pendingReports = $stmtPending->fetch(PDO::FETCH_ASSOC)['count'];

    $sqlResolved = "SELECT COUNT(*) AS count FROM incident_reports WHERE UPPER(status) = 'RESOLVED'";
    $stmtResolved = $conn->prepare($sqlResolved);
    $stmtResolved->execute();
    $resolvedReports = $stmtResolved->fetch(PDO::FETCH_ASSOC)['count'];

    // Fetch reports for the table
    $sqlReports = "SELECT id, name, title, description, date_submitted, status FROM incident_reports";
    $stmtReports = $conn->prepare($sqlReports);
    $stmtReports->execute();
    $reports = $stmtReports->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

function truncateDescription($description, $maxWords = 12) {
    $words = explode(' ', $description);
    if (count($words) > $maxWords) {
        return implode(' ', array_slice($words, 0, $maxWords)) . '...';
    }
    return $description;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/Reports.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/png" href="assets/Southside.png">
</head>
<body>
<?php 
    $pageTitle = "Incident Monitoring";
    include 'header.php';
    include 'sidebar.php'; 
?> 

<div class="main-content px-4">
    <!-- Summary Cards -->
    <div class="statistic-container">
        <div class="row justify-content-center align-items-center gap-5">
            <div class="col-auto d-flex justify-content-center">
                <div class="card card-request">
                    <div class="card-content">
                        <h2 class="card-title">Total Reports</h2>
                        <div class="card-text">
                            <?php echo $totalReports; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-auto d-flex justify-content-center">
                <div class="card card-pending">
                    <div class="card-content">
                        <h2 class="card-title">Pending</h2>
                        <div class="card-text">
                            <?php echo $pendingReports; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-auto d-flex justify-content-center">
                <div class="card card-approved">
                    <div class="card-content">
                        <h2 class="card-title">Resolved</h2>
                        <div class="card-text">
                            <?php echo $resolvedReports; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th data-sort="string">Name</th>
                    <th data-sort="string">Title</th>
                    <th data-sort="string">Description</th>
                    <th data-sort="date">Date Submitted</th>
                    <th data-sort="status">Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($reports)) {
                    foreach ($reports as $report) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($report['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($report['title']) . "</td>";
                        echo "<td>" . htmlspecialchars(truncateDescription($report['description'])) . "</td>";
                        echo "<td>" . date('m/d/Y', strtotime($report['date_submitted'])) . "</td>";
                        
                        // Status with color coding and badges
                        $statusBadgeClass = '';
                        $status = strtolower($report['status']);
                        switch($status) {
                            case 'pending':
                                $statusBadgeClass = 'badge bg-warning';
                                break;
                            case 'resolved':
                                $statusBadgeClass = 'badge bg-success';
                                break;
                        }
                        echo "<td><span class='{$statusBadgeClass}'>" . ucfirst(htmlspecialchars($status)) . "</span></td>";

                        // Action button with appropriate styling
                        if ($status === 'resolved') {
                            echo '<td><button class="action-button button-approved" disabled>Resolved</button></td>';
                        } else {
                            echo '<td><a href="report_verify.php?id=' . htmlspecialchars($report['id']) . '" class="action-button">View</a></td>';
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No reports found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
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
                const statusOrderAsc = { "pending": 1, "resolved": 2 };
                const statusOrderDesc = { "resolved": 1, "pending": 2 };
                const currentOrder = sortDirection[type] === 1 ? statusOrderAsc : statusOrderDesc;
                sortedRows = rows.sort((a, b) => currentOrder[a.cells[index].innerText.toLowerCase()] - currentOrder[b.cells[index].innerText.toLowerCase()]);
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
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn = null;
?>
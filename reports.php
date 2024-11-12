<?php
session_start();
include 'db.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php"); // Redirect to the login page if not logged in
    exit;
}

try {
    // Query to fetch the total number of incident reports
    $sqlTotal = "SELECT COUNT(*) AS count FROM incident_reports";
    $stmtTotal = $conn->prepare($sqlTotal);
    $stmtTotal->execute();
    $totalReports = $stmtTotal->fetch(PDO::FETCH_ASSOC)['count'];

    // Query to fetch the number of reports with status 'Pending'
    $sqlPending = "SELECT COUNT(*) AS count FROM incident_reports WHERE LOWER(status) = 'Pending'";
    $stmtPending = $conn->prepare($sqlPending);
    $stmtPending->execute();
    $pendingReports = $stmtPending->fetch(PDO::FETCH_ASSOC)['count'];

    // Query to fetch the number of reports with status 'Resolved'
    $sqlResolved = "SELECT COUNT(*) AS count FROM incident_reports WHERE LOWER(status) = 'Resolved'";
    $stmtResolved = $conn->prepare($sqlResolved);
    $stmtResolved->execute();
    $resolvedReports = $stmtResolved->fetch(PDO::FETCH_ASSOC)['count'];

    // Query to fetch all incident reports for the table
    $sqlReports = "SELECT id, name, title, description, date_submitted, status FROM incident_reports";
    $stmtReports = $conn->prepare($sqlReports);
    $stmtReports->execute();
    $reports = $stmtReports->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/Reports.css">
</head>
<body>
<?php 
    $pageTitle = "Incident Report and Monitoring";
    include 'header.php';
?>
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="container">
        <!-- Dynamic summary boxes -->
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="stat-box total-reports text-center py-3">
                    <h4>Total Reports</h4>
                    <div class="stat-number"><?php echo $totalReports; ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box pending  text-center py-3">
                    <h4>Pending</h4>
                    <div class="stat-number"><?php echo $pendingReports; ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box resolved  text-center py-3">
                    <h4>Resolved</h4>
                    <div class="stat-number"><?php echo $resolvedReports; ?></div>
                </div>
            </div>
        </div>

        <!-- Incident Reports Table -->
        <table class="table table-bordered">
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
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($report['name']); ?></td>
                        <td><?php echo htmlspecialchars($report['title']); ?></td>
                        <td><?php echo htmlspecialchars($report['description']); ?></td>
                        <td><?php echo date('m/d/Y', strtotime($report['date_submitted'])); ?></td>
                        <td><?php echo htmlspecialchars($report['status']); ?></td>
                        <td><a href="report_verify.php?id=<?php echo $report['id']; ?>" class="action-button">View</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

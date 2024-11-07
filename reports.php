<?php
session_start();
include 'db.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php"); // Redirect to the login page if not logged in
    exit;
}

try {
    // Query to fetch incident reports
    $sql = "SELECT id, name, title, description, date_submitted, status FROM incident_reports";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

<div class="main-content p-4">
    <div class="statistic-boxes d-flex justify-content-around mb-4 p-3 rounded">
    <div class="stat-box text-white p-3 rounded" style="background-color: #73C2FB; width: 18.75rem; height: 9.375rem;">
        <h5 class="text-center">Total Reports</h5>
        <p class="text-center">555</p>
    </div>
    <div class="stat-box text-dark p-3 rounded" style="background-color: #F5A623; width: 18.75rem; height: 9.375rem;">
        <h5 class="text-center">Pending</h5>
        <p class="text-center">555</p>
    </div>
    <div class="stat-box text-white p-3 rounded" style="background-color: #4CAF50; width: 18.75rem; height: 9.375rem;">
        <h5 class="text-center">Resolved</h5>
        <p class="text-center">555</p>
    </div>
</div>

    <div class="container">
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

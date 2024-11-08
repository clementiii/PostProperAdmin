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
    <style>
        .stat-box {
            color: #ffffff;
            flex: 1;
            max-width: 20rem; /* Approximately 300px equivalent */
            height: 9.375rem; /* Approximately 150px equivalent */
            border-radius: 0.5rem;
            opacity: 0.9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Shadow to match the reference */
            transition: transform 0.2s ease; 
        }
        .stat-box h4 {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            width: 15.625rem; /* Approximately 250px equivalent */
            height: 3.125rem; /* Approximately 50px equivalent */
            background: rgba(255, 255, 255, 0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            margin-top: 0.5rem;
            background-color: rgba(255, 255, 255, 0.15); /* Light overlay background */
        }
        .total-reports {
            background: linear-gradient(to bottom, #4A9ED9, #73C2FB);
        }
        .pending {
            background: linear-gradient(to bottom, #D68910, #F5B041);
        }
        .resolved {
            background: linear-gradient(to bottom, #229954, #27AE60);
        }
    </style>
</head>
<body>
<?php 
    $pageTitle = "Incident Report and Monitoring";
    include 'header.php';
?>
<?php include 'sidebar.php'; ?>

<div class="main-content p-4">
    <div class="container">
    <div class="row justify-content-center" style="margin-bottom: 2.813rem;">
            <div class="col-md-4">
                <div class="stat-box total-reports bg-primary text-center py-3">
                    <h4>Total Reports</h4>
                    <div class="stat-number">200</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box pending bg-success text-center py-3">
                    <h4>Pending</h4>
                    <div class="stat-number">130</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box resolved bg-secondary text-center py-3">
                    <h4>Resolved</h4>
                    <div class="stat-number">70</div>
                </div>
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
</div>

</body>
</html>

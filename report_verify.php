<?php
session_start();
include 'db.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php"); // Redirect to the login page if not logged in
    exit;
}

// Get the report ID from the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $report_id = $_GET['id'];

    try {
        // Query to fetch the specific report based on the ID
        $sql = "SELECT id, name, title, description, date_submitted, status FROM incident_reports WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $report_id, PDO::PARAM_INT);
        $stmt->execute();
        $report = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the report exists
        if (!$report) {
            echo "Report not found.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
} else {
    echo "Invalid report ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Report Verification</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" href="css/report_verify.css">
   <style>
       .bg-purple {
           background-color: #4A148C !important;
       }
       .header-text {
           font-size: 1.5rem;
           font-weight: bold;
       }
       .form-control {
           max-width: 600px;
           margin: auto;
       }
       .image-container img {
           width: 100px;
           height: 100px;
           margin: 5px;
       }
       .button-group {
           display: flex;
           justify-content: center;
           gap: 10px;
       }
       /* Center container vertically and horizontally */
       .center-container {
           display: flex;
           align-items: center;
           justify-content: center;
           min-height: 100vh;
       }
       /* Add padding to move content away from sidebar */
       .content-container {
           margin-left: 250px; /* Adjust this based on sidebar width */
           padding: 20px;
       }
   </style>
</head>
<body>
  <?php 
        $pageTitle = "Report Verification";
        include 'header.php';
        include 'sidebar.php';
    ?>

  <!-- Centering the container with padding on the left side -->
<div class="center-container content-container">
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-purple text-white text-center">
                <span class="header-text">Incident Report and Monitoring</span>
            </div>
            <div class="card-body">
                <form action="update_report.php" method="post" class="text-center">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title:</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($report['title']); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <div class="form-control" style="height: auto; white-space: pre-wrap;"><?php echo htmlspecialchars($report['description']); ?></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Images:</label>
                        <div class="image-container d-flex justify-content-center">
                            <img src="assets/profile.jpg" alt="Incident Image 1" class="img-thumbnail">
                            <img src="assets/profile.jpg" alt="Incident Image 2" class="img-thumbnail">
                            <img src="assets/profile.jpg" alt="Incident Image 3" class="img-thumbnail">
                        </div>
                    </div>
                    <div class="button-group mt-4">
                        <button type="submit" class="btn btn-success" name="status" value="resolved">Resolved</button>
                        <button type="submit" class="btn btn-warning" name="status" value="pending">Pending</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
include 'db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $report_id = $_GET['id'];

    try {
        $sql = "SELECT id, title, description FROM incident_reports WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $report_id, PDO::PARAM_INT);
        $stmt->execute();
        $report = $stmt->fetch(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/report_verify.js" defer></script>
    
</head>
<body>
  <?php 
        $pageTitle = "Report Verification";
        include 'header.php';
        include 'sidebar.php';
    ?>

  <!-- Main content container -->
  <div class="main-container" style="padding-top: 20px;">
    
    <!-- Back Button with Font Awesome icon -->
    <button class="back-btn btn-secondary" onclick="goBack()">
      <i class="fas fa-arrow-left"></i> Back
    </button>

    <div class="form-group">
      <label for="reportTitle" class="form-label">Title:</label>
      <input type="text" class="form-control" id="reportTitle" value="<?php echo htmlspecialchars($report['title']); ?>" readonly>
    </div>

    <div class="form-group">
      <label for="reportDescription" class="form-label">Description:</label>
      <textarea class="form-control" id="reportDescription" rows="3" readonly><?php echo htmlspecialchars($report['description']); ?></textarea>
    </div>

    <div class="form-group">
      <label class="form-label">Images:</label>
      <div class="horizontal-images">
        <img src="assets/sample id.jpg" class="img-thumbnail zoomable-image" alt="Incident Image 1">
        <img src="assets/profile.jpg" class="img-thumbnail zoomable-image" alt="Incident Image 2">
        <img src="assets/sample id.jpg" class="img-thumbnail zoomable-image" alt="Incident Image 3">
      </div>
    </div>

    <div class="d-flex justify-content-start" style="padding-top: 1rem;">
      <button class="action-btn me-2" id="resolvedBtn">Resolved</button>
    </div>
  </div>

  <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
          <img src="" id="modalImage" class="img-fluid" alt="Zoomed Image">
        </div>
      </div>
    </div>
  </div>
  <!-- Confirmation Modal -->
  <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmModalLabel">Confirm Resolution</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to mark this report as resolved?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="confirmResolve">Confirm</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
   function goBack() {
      window.history.back();
    }

    document.getElementById("resolvedBtn").addEventListener("click", function () {
      new bootstrap.Modal(document.getElementById("confirmModal")).show();
    });

    document.getElementById("confirmResolve").addEventListener("click", function () {
      // Perform the action to mark the report as resolved here, e.g., AJAX request to update the database
      // After successful resolution, close the modal and show success message
      new bootstrap.Modal(document.getElementById("confirmModal")).hide();
      alert("The report has been marked as resolved.");
    });

    document.querySelectorAll('.zoomable-image').forEach(img => {
      img.addEventListener('click', function() {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = this.src;
        new bootstrap.Modal(document.getElementById('imageModal')).show();
      });
    });
  </script>
</body>
</html>

<?php
// Include database connection
include 'db.php';

// Get the transaction ID from the URL
$documentId = isset($_GET['id']) ? $_GET['id'] : 0; // Default to 0 if 'id' is not set

// Fetch the document request details from the database based on the ID
$query = "SELECT * FROM document_requests WHERE Id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $documentId, PDO::PARAM_INT);
$stmt->execute();
$documentRequest = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the document request exists
if (!$documentRequest) {
    echo "<p>Document request not found.</p>";
    exit;
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the new status and reason (if applicable) from the form
    $newStatus = $_POST['status'];
    $reason = isset($_POST['reason']) ? $_POST['reason'] : null;

    // Validate reason for rejection if the status is "Rejected"
    if ($newStatus === 'Rejected' && empty($reason)) {
        echo "<p style='color: red;'>Please provide a reason for rejection.</p>";
        exit;
    }

    // Update the status and rejection reason in the database
    $updateQuery = "UPDATE document_requests SET Status = :status, rejection_reason = :reason WHERE Id = :id";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':status', $newStatus, PDO::PARAM_STR);
    $updateStmt->bindParam(':id', $documentId, PDO::PARAM_INT);
    $updateStmt->bindParam(':reason', $reason, PDO::PARAM_STR);
    $updateStmt->execute();

    // Redirect to documents.php after saving
    header('Location: documents.php');
    exit;
}

// Close the database connection
$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Verification</title>
    <link rel="stylesheet" href="css/document_verify.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php 
        $pageTitle = "Document Verification";
        include 'header.php';
        include 'sidebar.php';
    ?>

    <div class="main-container mt-5">
        <button onclick="history.back()" class="btn btn-secondary mb-3" style="font-size: 1.25rem;">
            <i class="fas fa-arrow-left"></i>
        </button>
        <h2 class="text-center mb-4">Document Verification</h2>
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="assets/profile.jpg" class="rounded-circle img-thumbnail" alt="User Profile" width="150">
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($documentRequest['Name']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($documentRequest['Address']); ?></p>
                        <p><strong>TIN No:</strong> <?php echo htmlspecialchars($documentRequest['TIN_No']); ?></p>
                        <p><strong>CTC No:</strong> <?php echo htmlspecialchars($documentRequest['CTC_No']); ?></p>
                        <p><strong>Alias:</strong> <?php echo htmlspecialchars($documentRequest['Alias']); ?></p>
                        <p><strong>Age:</strong> <?php echo htmlspecialchars($documentRequest['Age']); ?> years old</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Length of Stay:</strong> <?php echo htmlspecialchars($documentRequest['LengthOfStay']); ?> years</p>
                        <p><strong>Citizenship:</strong> <?php echo htmlspecialchars($documentRequest['Citizenship']); ?></p>
                        <p><strong>Gender:</strong> <?php echo htmlspecialchars($documentRequest['Gender']); ?></p>
                        <p><strong>Civil Status:</strong> <?php echo htmlspecialchars($documentRequest['CivilStatus']); ?></p>
                        <p><strong>Purpose:</strong> <?php echo htmlspecialchars($documentRequest['Purpose']); ?></p>
                        <p><strong>Status:</strong> 
                            <form method="POST" id="statusForm">
                                <select id="statusSelect" name="status" class="form-select">
                                    <option value="Pending" <?php echo ($documentRequest['Status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Approved" <?php echo ($documentRequest['Status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                    <option value="Rejected" <?php echo ($documentRequest['Status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                                
                                <!-- Reason for Rejection Text Field -->
                                <div id="reasonContainer" class="mt-3" style="display: none;">
                                    <label for="reason" class="form-label">Reason for Rejection:</label>
                                    <input type="text" id="reason" name="reason" class="form-control" placeholder="Enter reason for rejection">
                                </div>
                            </form>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <img src="assets/sample id.jpg" class="img-thumbnail zoomable" alt="User ID" data-bs-toggle="modal" data-bs-target="#imageModal">
            </div>
            <div class="col-md-6">
                <img src="assets/sample id.jpg" class="img-thumbnail zoomable" alt="User ID" data-bs-toggle="modal" data-bs-target="#imageModal">
            </div>
        </div>
        
        <div class="text-center mt-4">
            <button id="saveBtn" class="action-btn" data-bs-toggle="modal" data-bs-target="#confirmModal">Save</button>
        </div>
    </div>
    
    <!-- Image Zoom Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body d-flex justify-content-center">
                    <img id="modalImage" src="" class="img-fluid modal-image" alt="Zoomed Image">
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Save</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to save the changes?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmSaveBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 and JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Set clicked image in the modal
        const imageModal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');

        document.querySelectorAll('.zoomable').forEach(image => {
            image.addEventListener('click', function() {
                modalImage.src = this.src;
            });
        });

        // Toggle visibility of Reason for Rejection text field based on selected status
        document.getElementById('statusSelect').addEventListener('change', function () {
            const reasonContainer = document.getElementById('reasonContainer');
            if (this.value === 'Rejected') {
                reasonContainer.style.display = 'block';
            } else {
                reasonContainer.style.display = 'none';
            }
        });

        // Confirmation modal actions
        document.getElementById('confirmSaveBtn').addEventListener('click', function() {
            document.getElementById('statusForm').submit(); // Submit the form if confirmed
        });
    </script>
</body>
</html>

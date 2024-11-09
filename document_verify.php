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
    // Get the new status from the form
    $newStatus = $_POST['status'];
    
    // Update the status in the database
    $updateQuery = "UPDATE document_requests SET Status = :status WHERE Id = :id";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bindParam(':status', $newStatus, PDO::PARAM_STR);
    $updateStmt->bindParam(':id', $documentId, PDO::PARAM_INT);
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
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styling for centering and layout */
        .main-container {
            max-width: 900px; /* Adjust width as needed */
            margin: 0 auto; /* Center the container */
            padding: 20px;
        }
        .img-thumbnail {
            cursor: pointer;
        }
        .action-btn{
            background-color: #4A148C !important;
            color: #ffffff;
            width: 7vw;
            height: 4vh;
            border-radius: 8px; /* Rounded corners */
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php 
        $pageTitle = "Document Verification";
        include 'header.php';
        include 'sidebar.php';
    ?>

    <div class="main-container mt-5">
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
                            <!-- The form will only be submitted on Save button click -->
                            <form method="POST" id="statusForm">
                                <select id="statusSelect" name="status" class="form-select">
                                    <option value="Pending" <?php echo ($documentRequest['Status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Approved" <?php echo ($documentRequest['Status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                    <option value="Rejected" <?php echo ($documentRequest['Status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                                </select>
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
            <button id="saveBtn" class="action-btn">Save</button>
        </div>
    </div>

    <!-- Image Zoom Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="path_to_id_image.jpg" class="img-fluid" alt="Zoomed ID">
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 and JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // When Save button is clicked, submit the form
        document.getElementById('saveBtn').addEventListener('click', function() {
            document.getElementById('statusForm').submit(); // Submit the form to update the status
        });
    </script>
</body>
</html>

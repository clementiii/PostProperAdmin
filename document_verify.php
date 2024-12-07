<?php
    include 'db.php';

    $documentId = isset($_GET['id']) ? $_GET['id'] : 0;

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

    // Query to get document details
    $query = "SELECT * FROM document_requests WHERE Id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $documentId, PDO::PARAM_INT);
    $stmt->execute();
    $documentRequest = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$documentRequest) {
        echo "<p>Document request not found.</p>";
        exit;
    }

    // Handle status update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newStatus = $_POST['status'];
        $reason = isset($_POST['reason']) ? $_POST['reason'] : null;

        if ($newStatus === 'Rejected' && empty($reason)) {
            echo "<p style='color: red;'>Please provide a reason for rejection.</p>";
            exit;
        }

        $updateQuery = "UPDATE document_requests SET Status = :status, rejection_reason = :reason WHERE Id = :id";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(':status', $newStatus, PDO::PARAM_STR);
        $updateStmt->bindParam(':id', $documentId, PDO::PARAM_INT);
        $updateStmt->bindParam(':reason', $reason, PDO::PARAM_STR);
        $updateStmt->execute();

        header('Location: documents.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Verification</title>
    <link rel="stylesheet" href="css/document_verify.css">
    <link rel="stylesheet" href="css/root.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/Southside.png">
</head>
<body>
    <?php 
        $pageTitle = "Document Verification";
        include 'header.php';
        include 'sidebar.php';
    ?>

    <div class="main-container">
        <div class="document-header mb-4">
            <button onclick="history.back()" class="btn btn-secondary" style="font-size: 1.1rem;">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            <h2 class="page-title text-center">Document Verification</h2>
        </div>

        <div class="verification-container">
            <!-- Document Info Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Document Information</h4>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="info-group">
                                <h5 class="section-title">Personal Details</h5>
                                <div class="info-item">
                                    <strong>Name:</strong> 
                                    <span><?php echo htmlspecialchars($documentRequest['Name']); ?></span>
                                </div>
                                <div class="info-item">
                                    <strong>Address:</strong> 
                                    <span><?php echo htmlspecialchars($documentRequest['Address']); ?></span>
                                </div>
                                <div class="info-item">
                                    <strong>Age:</strong> 
                                    <span><?php echo htmlspecialchars($documentRequest['Age']); ?> years old</span>
                                </div>
                                <div class="info-item">
                                    <strong>Birthday:</strong> 
                                    <span><?php echo htmlspecialchars($documentRequest['birthday']); ?></span>
                                </div>
                                <div class="info-item">
                                    <strong>Place of Birth:</strong> 
                                    <span><?php echo htmlspecialchars($documentRequest['PlaceOfBirth']); ?></span>
                                </div>
                                <div class="info-item">
                                    <strong>Alias:</strong> 
                                    <span><?php echo htmlspecialchars($documentRequest['Alias']); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="info-group">
                                <h5 class="section-title">Additional Information</h5>
                                <div class="info-item">
                                    <strong>Citizenship:</strong> 
                                    <span><?php echo htmlspecialchars($documentRequest['Citizenship']); ?></span>
                                </div>
                                <div class="info-item">
                                    <strong>Occupation:</strong> 
                                    <span><?php echo htmlspecialchars($documentRequest['Occupation']); ?></span>
                                </div>
                                <div class="info-item">
                                    <strong>Gender:</strong> 
                                    <span><?php echo htmlspecialchars($documentRequest['Gender']); ?></span>
                                </div>
                                <div class="info-item">
                                    <strong>Civil Status:</strong> 
                                    <span><?php echo htmlspecialchars($documentRequest['CivilStatus']); ?></span>
                                </div>
                                <div class="info-item">
                                    <strong>Length of Stay:</strong> 
                                    <span><?php echo htmlspecialchars($documentRequest['LengthOfStay']); ?> years</span>
                                </div>
                            </div>

                            <div class="info-group mt-4">
                                <h5 class="section-title">Document Details</h5>
                                <div class="info-item">
                                    <strong>Document Type:</strong> 
                                    <span><?php echo htmlspecialchars($documentRequest['DocumentType']); ?></span>
                                </div>
                                <div class="info-item">
                                    <strong>Purpose:</strong> 
                                    <span><?php echo htmlspecialchars($documentRequest['Purpose']); ?></span>
                                </div>
                                <div class="info-item">
                                    <strong>TIN No:</strong> 
                                    <span><?php echo htmlspecialchars($documentRequest['TIN_No']); ?></span>
                                </div>
                                <div class="info-item">
                                    <strong>CTC No:</strong> 
                                    <span><?php echo htmlspecialchars($documentRequest['CTC_No']); ?></span>
                                </div>
                                <div class="info-item">
                                    <strong>Quantity:</strong> 
                                    <span><?php echo htmlspecialchars($documentRequest['Quantity']); ?></span>
                                </div>
                                <div class="info-item">
                                    <strong>Price:</strong> 
                                    <span><?php echo calculatePrice($documentRequest['DocumentType'], $documentRequest['Quantity']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Section -->
                    <div class="status-section mt-4">
                        <h5 class="section-title">Status Update</h5>
                        <form method="POST" id="statusForm" class="status-form">
                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <label class="form-label">Current Status</label>
                                    <select id="statusSelect" name="status" class="form-select">
                                        <option value="Pending" <?php echo ($documentRequest['Status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Approved" <?php echo ($documentRequest['Status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                        <option value="Rejected" <?php echo ($documentRequest['Status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                                        <option value="Cancelled" <?php echo ($documentRequest['Status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <?php if ($documentRequest['Status'] == 'Cancelled'): ?>
                                        <div class="info-item">
                                            <strong>Cancellation Reason:</strong> 
                                            <span><?php echo htmlspecialchars($documentRequest['cancellation_reason']); ?></span>
                                        </div>
                                        <?php endif; ?>
                                </div>
                                <div id="reasonContainer" class="col-md-6" style="display: none;">
                                    <label class="form-label">Reason for Rejection</label>
                                    <input type="text" id="reason" name="reason" class="form-control"
                                        placeholder="Enter reason for rejection"
                                        value="<?php echo htmlspecialchars($documentRequest['rejection_reason'] ?? ''); ?>">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Valid ID Section -->
            <!-- Valid ID Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Valid ID Images</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Front ID -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Front Side</h5>
                            <?php if (!empty($documentRequest['valid_id_front'])): ?>
                                <img src="<?php echo htmlspecialchars($documentRequest['valid_id_front']); ?>" 
                                    class="img-thumbnail zoomable" 
                                    alt="Valid ID Front"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#imageModal"
                                    style="width: 100%; height: 300px; object-fit: contain;">
                            <?php else: ?>
                                <div class="alert alert-info">No front ID image uploaded</div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Back ID -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Back Side</h5>
                            <?php if (!empty($documentRequest['valid_id_back'])): ?>
                                <img src="<?php echo htmlspecialchars($documentRequest['valid_id_back']); ?>" 
                                    class="img-thumbnail zoomable" 
                                    alt="Valid ID Back"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#imageModal"
                                    style="width: 100%; height: 300px; object-fit: contain;">
                            <?php else: ?>
                                <div class="alert alert-info">No back ID image uploaded</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="text-center">
                <button id="saveBtn" class="btn btn-primary btn-lg px-5" data-bs-toggle="modal" data-bs-target="#confirmModal">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Document Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modalImage" src="" class="img-fluid" alt="Document Preview" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>

    <!-- Confirmation Modal -->
    <div class="modal" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Image Modal Handler
    document.querySelectorAll('.zoomable').forEach(image => {
        image.addEventListener('click', function() {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = modal.querySelector('.modal-title');
            
            modalImage.src = this.src;
            modalTitle.textContent = this.alt; // Updates the modal title to show which side is being viewed
            
            // Update modal title based on the image being viewed
            modalTitle.textContent = this.alt === 'Valid ID Front' ? 'Front Side of ID' : 'Back Side of ID';
        });
    });

    // Status Change Handler
    document.getElementById('statusSelect').addEventListener('change', function() {
        const reasonContainer = document.getElementById('reasonContainer');
        const reasonInput = document.getElementById('reason');
        
        if (this.value === 'Rejected') {
            reasonContainer.style.display = 'block';
        } else {
            reasonContainer.style.display = 'none';
            reasonInput.value = '';
        }
    });

    // Initial Status Check
    if (document.getElementById('statusSelect').value === 'Rejected') {
        document.getElementById('reasonContainer').style.display = 'block';
    }

    // Save Confirmation Handler
    document.getElementById('confirmSaveBtn').addEventListener('click', function() {
        const status = document.getElementById('statusSelect').value;
        const reasonInput = document.getElementById('reason');
        
        if (status === 'Rejected' && !reasonInput.value.trim()) {
            alert('Please provide a reason for rejection.');
            return;
        }
        
        document.getElementById('statusForm').submit();
    });

    // Modal cleanup handlers
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
    });

    document.getElementById('confirmModal').addEventListener('hidden.bs.modal', function() {
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
    });
</script>
</body>
</html>
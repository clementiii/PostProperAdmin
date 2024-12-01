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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="icon" type="image/png" href="assets/Southside.png">
        <style>
            .img-thumbnail.zoomable {
                cursor: pointer;
                transition: transform 0.3s ease;
                max-height: 400px;
                width: 100%;
                object-fit: contain;
            }
            .img-thumbnail.zoomable:hover {
                transform: scale(1.05);
            }
            .modal-body img {
                max-height: 80vh;
                width: auto;
            }
            .alert-info {
                background-color: #f8f9fa;
                border-color: #ddd;
                color: #6c757d;
                text-align: center;
                padding: 2rem;
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
            <button onclick="history.back()" class="btn btn-secondary mb-3" style="font-size: 1.25rem;">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h2 class="text-center mb-4">Document Verification</h2>
            <div class="row">
                <div class="col-md-4 text-center">
                    
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
                            <p><strong>Birthday:</strong> <?php echo htmlspecialchars($documentRequest['birthday']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Length of Stay:</strong> <?php echo htmlspecialchars($documentRequest['LengthOfStay']); ?> years</p>
                            <p><strong>Citizenship:</strong> <?php echo htmlspecialchars($documentRequest['Citizenship']); ?></p>
                            <p><strong>Gender:</strong> <?php echo htmlspecialchars($documentRequest['Gender']); ?></p>
                            <p><strong>Civil Status:</strong> <?php echo htmlspecialchars($documentRequest['CivilStatus']); ?></p>
                            <p><strong>Purpose:</strong> <?php echo htmlspecialchars($documentRequest['Purpose']); ?></p>
                            <p><strong>Document Type:</strong> <?php echo htmlspecialchars($documentRequest['DocumentType']); ?></p>
                            <p><strong>Quantity:</strong> <?php echo htmlspecialchars($documentRequest['Quantity']); ?></p>
                            <p><strong>Price:</strong> <?php echo calculatePrice($documentRequest['DocumentType'], $documentRequest['Quantity']); ?></p>
                            <p><strong>Status:</strong> 
                                <form method="POST" id="statusForm">
                                    <select id="statusSelect" name="status" class="form-select">
                                        <option value="Pending" <?php echo ($documentRequest['Status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Approved" <?php echo ($documentRequest['Status'] == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                                        <option value="Rejected" <?php echo ($documentRequest['Status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
                                    </select>
                                    
                                    <div id="reasonContainer" class="mt-3" style="display: none;">
                                        <label for="reason" class="form-label">Reason for Rejection:</label>
                                        <input type="text" id="reason" name="reason" class="form-control" 
                                            placeholder="Enter reason for rejection"
                                            value="<?php echo htmlspecialchars($documentRequest['rejection_reason'] ?? ''); ?>">
                                    </div>
                                </form>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Display Valid ID -->
            <div class="row mt-4">
                <h4 class="mb-3">Uploaded Valid ID</h4>
                <div class="col-12">
                    <?php if (!empty($documentRequest['valid_id'])): ?>
                        <img src="<?php echo htmlspecialchars($documentRequest['valid_id']); ?>" 
                            class="img-thumbnail zoomable" 
                            alt="Valid ID" 
                            data-bs-toggle="modal" 
                            data-bs-target="#imageModal">
                    <?php else: ?>
                        <div class="alert alert-info">No valid ID uploaded</div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <button id="saveBtn" class="action-btn" data-bs-toggle="modal" data-bs-target="#confirmModal">Save</button>
            </div>
        </div>
        
        <!-- Image Modal -->
        <div class="modal fade " id="imageModal" tabindex="1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Document Preview</h5>
                        <button type="button" class="btn-close close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center p-0">
                        <img id="modalImage" src="" class="img-fluid" alt="Document Preview">
                    </div>
                </div>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div class="modal custom-modal" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">Confirm Save</h5>
                        <button type="button" class="close-btn close-modal" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
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

        <!-- Bootstrap and JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Set clicked image in the modal
            document.querySelectorAll('.zoomable').forEach(image => {
                image.addEventListener('click', function() {
                    document.getElementById('modalImage').src = this.src;
                });
            });

        document.getElementById('statusSelect').addEventListener('change', function () {
        const reasonContainer = document.getElementById('reasonContainer');
        const reasonInput = document.getElementById('reason');
        
        if (this.value === 'Rejected') {
            reasonContainer.style.display = 'block';
        } else {
            reasonContainer.style.display = 'none';
            reasonInput.value = ''; // Clear the reason input
        }
    });

    // Show reason container if status is "Rejected" on page load
    if (document.getElementById('statusSelect').value === 'Rejected') {
        document.getElementById('reasonContainer').style.display = 'block';
    } else {
        document.getElementById('reasonContainer').style.display = 'none';
        document.getElementById('reason').value = ''; // Ensure reason is cleared on page load if not rejected
    }

    // Confirmation modal actions
    document.getElementById('confirmSaveBtn').addEventListener('click', function() {
        const status = document.getElementById('statusSelect').value;
        const reasonInput = document.getElementById('reason');
        
        if (status === 'Rejected' && !reasonInput.value.trim()) {
            alert('Please provide a reason for rejection.');
            return;
        }
        
        document.getElementById('statusForm').submit();
    });
            
            document.addEventListener('DOMContentLoaded', function() {
        // Remove any existing modal backdrop
        function removeModalBackdrop() {
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }

        // Run on page load
        removeModalBackdrop();

        // Image Modal Setup
        document.querySelectorAll('.zoomable').forEach(image => {
            image.addEventListener('click', function() {
                document.getElementById('modalImage').src = this.src;
                removeModalBackdrop(); // Ensure no backdrop lingers
            });
        });

        // Save Button and Modal Handling
        document.getElementById('saveBtn').addEventListener('click', removeModalBackdrop);
        
        // Close buttons in modals
        document.querySelectorAll('.custom-modal .btn-close').forEach(closeBtn => {
            closeBtn.addEventListener('click', removeModalBackdrop);
        });
    });
        </script>
    </body>
    </html>
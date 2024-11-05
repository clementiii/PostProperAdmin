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
        /* Custom style for the Save button */
        .btn-save {
            background-color: #61009F !important;
            color: white;
            width: 125px;
            height: 40px;
        }
    </style>
</head>
<body>
    <?php 
        $pageTitle = "Document Verification";
        include 'header.php';
        include 'sidebar.php';
    ?>

    <div class="main-content">
        <div class="container-fluid mt-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="assets/profile.jpg" alt="User Profile" class="profile-img">
                    </div>

                    <div class="details-section">
                        <div class="detail-item">
                            <strong>Name:</strong> Bernardo Batumbakal
                        </div>
                        <div class="detail-item">
                            <strong>Alias:</strong> Bernard
                        </div>
                        <div class="detail-item">
                            <strong>Address:</strong> Post Proper Southside, Taguig City
                        </div>
                        <div class="detail-item">
                            <strong>Length of Stay:</strong> 7 years
                        </div>
                        <div class="detail-item">
                            <strong>TIN No:</strong> 000-123-456-001
                        </div>
                        <div class="detail-item">
                            <strong>Age:</strong> 57 years old
                        </div>
                        <div class="detail-item">
                            <strong>CTC No:</strong> 000-123-456-001
                        </div>
                        <div class="detail-item">
                            <strong>Citizenship:</strong> Filipino
                        </div>
                        <div class="detail-item">
                            <strong>Purpose:</strong> Barangay Clearance
                        </div>
                        <div class="detail-item">
                            <strong>Gender:</strong> Male
                        </div>
                        <div class="detail-item">
                            <strong>Civil Status:</strong> Single
                        </div>
                    </div>

                    <div class="mt-4">
                        <strong>Status:</strong>
                        <select class="form-select status-select">
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>

                    <div class="mt-4 d-flex gap-4">
                        <div>
                            <a href="assets/sample id.jpg" target="_blank">
                                <img src="assets/sample id.jpg" alt="ID Image 1" class="id-img">
                            </a>
                        </div>
                        <div>
                            <a href="assets/sample id.jpg" target="_blank">
                                <img src="assets/sample id.jpg" alt="ID Image 2" class="id-img">
                            </a>
                        </div>
                    </div>

                    <!-- Left-aligned Save Button with Custom Styles -->
                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-save" data-bs-toggle="modal" data-bs-target="#saveModal">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Save Confirmation Modal -->
    <div class="modal fade" id="saveModal" tabindex="-1" aria-labelledby="saveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="saveModalLabel">Save Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to save the document verification?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmSave">Yes, save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('confirmSave').addEventListener('click', function() {
            // Handle save confirmation logic here (e.g., submit form or save data via AJAX)
            alert('Document verification saved successfully.'); // Placeholder for save action
            const modal = bootstrap.Modal.getInstance(document.getElementById('saveModal'));
            modal.hide();
        });
    </script>
</body>
</html>

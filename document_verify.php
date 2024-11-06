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
                        <p><strong>Name:</strong> Robert Youngstown</p>
                        <p><strong>Address:</strong> Post Proper Southside, Taguig City</p>
                        <p><strong>TIN No:</strong> 000-123-456-001</p>
                        <p><strong>CTC No:</strong> 000-123-456-001</p>
                        <p><strong>Alias:</strong> Bernard</p>
                        <p><strong>Age:</strong> 57 years old</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Length of Stay:</strong> 7 years</p>
                        <p><strong>Citizenship:</strong> Filipino</p>
                        <p><strong>Gender:</strong> Male</p>
                        <p><strong>Civil Status:</strong> Single</p>
                        <p><strong>Purpose:</strong> Barangay Clearance</p>
                        <p><strong>Status:</strong> 
                            <select id="statusSelect" class="form-select">
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
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
            <button id="saveBtn" class="action-btn ">Save</button>
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

    <!-- Save Confirmation Modal -->
    <div class="modal fade" id="saveModal" tabindex="-1" aria-labelledby="saveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="saveModalLabel">Confirm Save</h5>
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
        // Show Save Confirmation Modal
        document.getElementById('saveBtn').addEventListener('click', function() {
            var saveModal = new bootstrap.Modal(document.getElementById('saveModal'));
            saveModal.show();
        });

        // Confirm Save Button
        document.getElementById('confirmSaveBtn').addEventListener('click', function() {
            // Here you would add the save functionality
            alert("Changes saved successfully!");
            var saveModal = bootstrap.Modal.getInstance(document.getElementById('saveModal'));
            saveModal.hide();
        });
    </script>
</body>
</html>

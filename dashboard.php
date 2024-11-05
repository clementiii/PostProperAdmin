<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php"); // Redirect to the login page if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Post Proper Southside Barangay Information System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/DashboardStyle.css">
</head>
<body>

<?php include 'sidebar.php'; ?> 

<div class="main-content">
    <div class="header-section">
        <img src="assets/mckinley.jpg" alt="city">
        <h1 class="text-center mb-4">Welcome, Admin Joyce Madrigal</h1>
    </div>

    <div class="container mt-5">
        <!-- Summary Cards -->
        <div class="row text-center mb-4">
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Registered Residents</h5>
                        <h3 class="card-text">555</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Document Requests</h5>
                        <h3 class="card-text">125</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Incident Reports</h5>
                        <h3 class="card-text">67</h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scrollable Transactions Table -->
        <div class="card">
            <div class="card-header">
                Recent Document Requests
            </div>
            <div class="table-container">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Name</th>
                            <th>Document Type</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Date Requested</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Example Data Rows -->
                        <tr>
                            <td>TXN-20230927</td>
                            <td>Robert Youngstown</td>
                            <td>Barangay Clearance</td>
                            <td>2</td>
                            <td>100.00</td>
                            <td>10/12/2024</td>
                            <td>
                                <button class="btn btn-primary btn-sm" style="background-color: #61009F; color: white;"
                                        onclick="openModal('TXN-20230927', 'Robert Youngstown', 'Robert', 'Barangay Clearance', '10/12/2024', 2, '100.00', '165 Sampaguita St. Post Proper Southside, Taguig City', 'Male', 'Single', 'Gamer', '123-456-789-000', '123-456-789-000')">
                                    View
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>TXN-20230928</td>
                            <td>Angelica Santos</td>
                            <td>Barangay Certificate</td>
                            <td>2</td>
                            <td>100.00</td>
                            <td>10/11/2024</td>
                            <td>
                                <button class="btn btn-primary btn-sm" style="background-color: #61009F; color: white;"
                                        onclick="openModal('TXN-20230928', 'Angelica Santos', 'Angel', 'Barangay Certificate', '10/11/2024', 2, '100.00', '123 Sampaguita St. Post Proper Southside, Taguig City', 'Female', 'Married', 'Teacher', '123-456-789-111', '123-456-789-111')">
                                    View
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>TXN-20230929</td>
                            <td>Maria Gonzales</td>
                            <td>Certificate of Indigency</td>
                            <td>1</td>
                            <td>60.00</td>
                            <td>10/10/2024</td>
                            <td>
                                <button class="btn btn-primary btn-sm" style="background-color: #61009F; color: white;"
                                        onclick="openModal('TXN-20230929', 'Maria Gonzales', 'Maria', 'Certificate of Indigency', '10/10/2024', 1, '60.00', '321 Sampaguita St. Post Proper Southside, Taguig City', 'Female', 'Single', 'Nurse', '123-456-789-222', '123-456-789-222')">
                                    View
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #61009F;">
                <!-- Transaction ID as Header -->
                <h5 class="modal-title text-white" id="userModalLabel" style="font-size: 1.5rem; font-weight: bold;">
                    <span id="modalTransactionIDHeader"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Transaction ID:</strong> <span id="modalTransactionID"></span></p>
                <p><strong>Name:</strong> <span id="modalName"></span></p>
                <p><strong>Alias:</strong> <span id="modalAlias"></span></p>
                <p><strong>Document Type:</strong> <span id="modalDocumentType"></span></p>
                <p><strong>Date Requested:</strong> <span id="modalDateRequested"></span></p>
                <p><strong>Quantity:</strong> <span id="modalQuantity"></span></p>
                <p><strong>Price:</strong> <span id="modalPrice"></span></p>
                <p><strong>Address:</strong> <span id="modalAddress"></span></p>
                <p><strong>Gender:</strong> <span id="modalGender"></span></p>
                <p><strong>Civil Status:</strong> <span id="modalCivilStatus"></span></p>
                <p><strong>Occupation:</strong> <span id="modalOccupation"></span></p>
                <p><strong>TIN #:</strong> <span id="modalTIN"></span></p>
                <p><strong>CTC #:</strong> <span id="modalCTC"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
// Function to open modal and populate with data
// Function to open modal and populate with data
function openModal(transactionID, name, alias, documentType, dateRequested, quantity, price, address, gender, civilStatus, occupation, tin, ctc) {
    // Set Transaction ID as the header title
    document.getElementById('modalTransactionIDHeader').textContent = transactionID;

    // Populate modal body details
    document.getElementById('modalTransactionID').textContent = transactionID;
    document.getElementById('modalName').textContent = name;
    document.getElementById('modalAlias').textContent = alias;
    document.getElementById('modalDocumentType').textContent = documentType;
    document.getElementById('modalDateRequested').textContent = dateRequested;
    document.getElementById('modalQuantity').textContent = quantity;
    document.getElementById('modalPrice').textContent = price;
    document.getElementById('modalAddress').textContent = address;
    document.getElementById('modalGender').textContent = gender;
    document.getElementById('modalCivilStatus').textContent = civilStatus;
    document.getElementById('modalOccupation').textContent = occupation;
    document.getElementById('modalTIN').textContent = tin;
    document.getElementById('modalCTC').textContent = ctc;

    var userModal = new bootstrap.Modal(document.getElementById('userModal'));
    userModal.show();
}

</script>
</body>
</html>

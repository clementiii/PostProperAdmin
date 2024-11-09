<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php"); // Redirect to the login page if not logged in
    exit;
}

// Database connection
require 'db.php';

// Fetch recent document requests for the table
$documentRequestsTableQuery = "SELECT * FROM document_requests ORDER BY DateRequested DESC LIMIT 5";
$documentRequestsTableResult = $conn->query($documentRequestsTableQuery)->fetchAll(PDO::FETCH_ASSOC);

// Fetch count of registered residents (user accounts)
$registeredResidentsQuery = "SELECT COUNT(*) AS count FROM user_accounts";
$registeredResidentsResult = $conn->query($registeredResidentsQuery)->fetch(PDO::FETCH_ASSOC);
$registeredResidentsCount = $registeredResidentsResult['count'];

// Fetch count of document requests
$documentRequestsQuery = "SELECT COUNT(*) AS count FROM document_requests";
$documentRequestsResult = $conn->query($documentRequestsQuery)->fetch(PDO::FETCH_ASSOC);
$documentRequestsCount = $documentRequestsResult['count'];

// Fetch count of incident reports
$incidentReportsQuery = "SELECT COUNT(*) AS count FROM incident_reports";
$incidentReportsResult = $conn->query($incidentReportsQuery)->fetch(PDO::FETCH_ASSOC);
$incidentReportsCount = $incidentReportsResult['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Post Proper Southside Barangay Information System</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/DashboardStyle.css">  
    <style>
    .card-title {
        font-size: 1.5rem;
        font-weight: 500;
        color: #ffffff;
    }
    .card-text {
        color: #ffffff;
        margin-top: 0.625rem;
        font-weight: 700;
        font-size: 2rem;
        border-radius: 0.5rem;
        background-color: rgba(255, 255, 255, 0.15);
    }
    </style>
    
</head>
<body>

  <?php include 'sidebar.php'; ?> 

<div class="main-content">
    <div class="header-section">
        <img src="assets/mckinley.jpg" alt="city">
        <h1 class="text-center mb-4">Welcome, Admin <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
            <a href="admin_profile.php">
        <img src="assets/profile.jpg" alt="Profile" class="profile-icon">
            </a>
    </div>
    <div class="container mt-5">
        <!-- Summary Cards -->
        <div class="row text-center mb-4">
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Registered Residents</h5>
                        <h3 class="card-text"><?php echo $registeredResidentsCount; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Document Requests</h5>
                        <h3 class="card-text"><?php echo $documentRequestsCount; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Incident Reports</h5>
                        <h3 class="card-text"><?php echo $incidentReportsCount; ?></h3>
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
                <table class="table mb-0" >
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
                        <?php foreach ($documentRequestsTableResult as $request): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($request['Id']); ?></td>
                                <td><?php echo htmlspecialchars($request['Name']); ?></td>
                                <td><?php echo htmlspecialchars($request['DocumentType']); ?></td>
                                <td><?php echo htmlspecialchars($request['Quantity']); ?></td>
                                <td><?php echo number_format($request['Quantity'] * 50, 2); ?></td>
                                <td><?php echo htmlspecialchars($request['DateRequested']); ?></td>
                                <td>
                                    <button class="action-btn btn-primary btn-sm"
                                            onclick="openModal('<?php echo htmlspecialchars($request['Id']); ?>', 
                                                                '<?php echo htmlspecialchars($request['Name']); ?>',
                                                                '<?php echo htmlspecialchars($request['Alias']); ?>',
                                                                '<?php echo htmlspecialchars($request['DocumentType']); ?>',
                                                                '<?php echo htmlspecialchars($request['DateRequested']); ?>',
                                                                '<?php echo htmlspecialchars($request['Quantity']); ?>',
                                                                '<?php echo number_format($request['Quantity'] * 50, 2); ?>',
                                                                '<?php echo htmlspecialchars($request['Address']); ?>',
                                                                '<?php echo htmlspecialchars($request['Gender']); ?>',
                                                                '<?php echo htmlspecialchars($request['CivilStatus']); ?>',
                                                                'Occupation',
                                                                'TIN',
                                                                'CTC')">
                                        View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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
function openModal(transactionID, name, alias, documentType, dateRequested, quantity, price, address, gender, civilStatus, occupation, tin, ctc) {
    document.getElementById('modalTransactionID').innerText = transactionID;
    document.getElementById('modalName').innerText = name;
    document.getElementById('modalAlias').innerText = alias;
    document.getElementById('modalDocumentType').innerText = documentType;
    document.getElementById('modalDateRequested').innerText = dateRequested;
    document.getElementById('modalQuantity').innerText = quantity;
    document.getElementById('modalPrice').innerText = price;
    document.getElementById('modalAddress').innerText = address;
    document.getElementById('modalGender').innerText = gender;
    document.getElementById('modalCivilStatus').innerText = civilStatus;
    document.getElementById('modalOccupation').innerText = occupation;
    document.getElementById('modalTIN').innerText = tin;
    document.getElementById('modalCTC').innerText = ctc;
    new bootstrap.Modal(document.getElementById('userModal')).show();
}
</script>
</body>
</html>

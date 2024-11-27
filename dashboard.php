<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php"); // Redirect to the login page if not logged in
    exit;
}

// Database connection
require 'db.php';

// Fetch the admin's profile picture from the database
$adminId = $_SESSION['admin_id']; // Ensure 'admin_id' is stored in session
try {
    $sql = "SELECT profile_picture FROM admin_accounts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$adminId]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Set profile picture path or default fallback image
    $profilePicture = !empty($admin['profile_picture']) ? $admin['profile_picture'] : 'assets/profile.jpg';
} catch (PDOException $e) {
    $profilePicture = 'assets/profile.jpg'; // Set fallback if error occurs
}



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
    <!-- Add right after the custom CSS link -->
<style>
.modal-backdrop {
    z-index: 1040;
}

.modal {
    z-index: 1050;
}

#logoutModal {
    z-index: 1060;
}

.modal-content {
    position: relative;
    z-index: 1051;
}

body.modal-open {
    overflow: hidden;
    padding-right: 0 !important;
}
</style>
    <link rel="icon" type="image/png" href="assets/Southside.png">
    
</head>
<body>

  <?php include 'sidebar.php'; ?> 
  
  <div class="main-content">
        <div class="header-section ">
            <img src="assets/mckinley.jpg" alt="city">
            <h1 class="text-center mb-4">Welcome, Admin <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
            <a href="admin_profile.php">
                <img src="<?php echo $profilePicture; ?>" alt="Profile" class="profile-icon rounded-circle">
            </a>
        </div>
    <div class="container mt-5 px-5">
        <!-- Summary Cards -->
        <div class="row text-center mb-4">
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="cards card-resident text-white mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Registered Residents</h5>
                        <h3 class="card-text"><?php echo $registeredResidentsCount; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="cards card-request text-white  mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Document Requests</h5>
                        <h3 class="card-text"><?php echo $documentRequestsCount; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="cards card-reports text-white mb-3">
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
                                <td><?php echo 'TXN-'?><?php echo htmlspecialchars($request['Id']); ?></td>
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
                                                                '<?php echo htmlspecialchars($request['TIN_No']); ?>',
                                                                '<?php echo htmlspecialchars($request['CTC_No']); ?>',
                                                                )">
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
<div id="userModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="modal-title">Transaction Details</h5>
            <span class="close-modal">&times;</span>
        </div>
        <div class="custom-modal-body">
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
            <p><strong>TIN #:</strong> <span id="modalTIN"></span></p>
            <p><strong>CTC #:</strong> <span id="modalCTC"></span></p>
        </div>
        <div class="custom-modal-footer">
            <button class="btn btn-secondary close-modal">Close</button>
        </div>
    </div>
</div>

<style>
.custom-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.custom-modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 0;
    border: 1px solid #888;
    width: 50%;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.custom-modal-header {
    padding: 15px 20px;
    background-color: #61009F;
    color: white;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.custom-modal-body {
    padding: 20px;
    max-height: 60vh;
    overflow-y: auto;
}

.custom-modal-footer {
    padding: 15px 20px;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
}

.close-modal {
    color: white;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close-modal:hover {
    color: #f0f0f0;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
function openModal(transactionID, name, alias, documentType, dateRequested, quantity, price, address, gender, civilStatus, tin, ctc) {
    // Set modal content
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
    document.getElementById('modalTIN').innerText = tin;
    document.getElementById('modalCTC').innerText = ctc;

    // Show modal
    document.getElementById('userModal').style.display = 'block';
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

// Close modal when clicking the X button or Close button
document.querySelectorAll('.close-modal').forEach(button => {
    button.onclick = function() {
        document.getElementById('userModal').style.display = 'none';
        document.body.style.overflow = 'auto'; // Restore scrolling
    }
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('userModal');
    if (event.target == modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Restore scrolling
    }
}
</script>
</body>
</html>

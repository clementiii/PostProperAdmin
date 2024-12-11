<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php");
    exit;
}

require 'db.php';

// Fetch admin's profile picture
try {
    $stmt = $conn->prepare("SELECT profile_picture FROM admin_accounts WHERE id = ?");
    $stmt->execute([$_SESSION['admin_id']]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    $profilePicture = !empty($admin['profile_picture']) ? $admin['profile_picture'] : 'assets/profile.jpg';
} catch (PDOException $e) {
    $profilePicture = 'assets/profile.jpg';
}

// Fetch statistics
$queries = [
    'residents' => "SELECT COUNT(*) AS count FROM user_accounts",
    'documents' => "SELECT COUNT(*) AS count FROM document_requests",
    'incidents' => "SELECT COUNT(*) AS count FROM incident_reports",
    'recent_requests' => "SELECT * FROM document_requests WHERE status = 'pending' ORDER BY DateRequested DESC LIMIT 5"
];

$stats = [];
foreach ($queries as $key => $query) {
    try {
        $result = $conn->query($query)->fetch(PDO::FETCH_ASSOC);
        $stats[$key] = ($key === 'recent_requests') ? $conn->query($query)->fetchAll(PDO::FETCH_ASSOC) : $result['count'];
    } catch (PDOException $e) {
        $stats[$key] = ($key === 'recent_requests') ? [] : 0;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Barangay Information System</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="css/DashboardStyle.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/Southside.png">
</head>
<body>
    <?php include 'sidebar.php'; ?> 
  
    <div class="main-content">
        <div class="header-section">
            <div class="header-overlay"></div>
            <img src="assets/mckinley.jpg" alt="city" class="header-image">
            <h1 class="welcome-text">Welcome, Admin <?php echo htmlspecialchars($_SESSION['name']); ?></h1>
            <a href="admin_profile.php" class="profile-link">
                <img src="<?php echo $profilePicture; ?>" alt="Profile" class="profile-icon">
            </a>
        </div>

        <div class="container mt-5 px-4">
            <!-- Stats Cards -->
            <div class="row stats-container g-4">
                <div class="col-md-4">
                    <div class="stat-card resident">
                        <i class="fas fa-users stat-icon"></i>
                        <div class="stat-info">
                            <h5>Registered Residents</h5>
                            <h3><?php echo $stats['residents']; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card documents">
                        <i class="fas fa-file-alt stat-icon"></i>
                        <div class="stat-info">
                            <h5>Document Requests</h5>
                            <h3><?php echo $stats['documents']; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card reports">
                        <i class="fas fa-exclamation-triangle stat-icon"></i>
                        <div class="stat-info">
                            <h5>Incident Reports</h5>
                            <h3><?php echo $stats['incidents']; ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Requests Table -->
            <div class="table-section mt-5">
                <div class="section-header">
                    <h4><i class="fas fa-clock"></i> Pending Document Requests</h4>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>TXN ID</th>
                                <th>Name</th>
                                <th>Document Type</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($stats['recent_requests'])): ?>
                                <?php foreach ($stats['recent_requests'] as $request): ?>
                                    <tr>
                                        <td>TXN-<?php echo htmlspecialchars($request['Id']); ?></td>
                                        <td><?php echo htmlspecialchars($request['Name']); ?></td>
                                        <td><?php echo htmlspecialchars($request['DocumentType']); ?></td>
                                        <td><?php echo htmlspecialchars($request['Quantity']); ?></td>
                                        <td>₱<?php echo number_format($request['Quantity'] * 50, 2); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($request['DateRequested'])); ?></td>
                                        <td><span class="badge bg-warning">Pending</span></td>
                                        <td>
                                            <button class="view-btn" onclick="viewDetails(<?php echo htmlspecialchars(json_encode($request)); ?>)">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No pending requests found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Modal content will be dynamically populated -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/dashboard.js"></script>
</body>
</html>
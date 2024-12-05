<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php");
    exit;
}

// Get user ID and ensure it's an integer
$userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT * FROM user_accounts WHERE id = :id");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    
    // Fetch the user data
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no user is found, redirect
    if (!$userData) {
        $_SESSION['error_message'] = "User not found";
        header("Location: users.php");
        exit;
    }

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Database error: " . $e->getMessage();
    header("Location: users.php");
    exit;
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    try {
        $newStatus = $_POST['status'];
        if (in_array($newStatus, ['pending', 'verified', 'rejected'], true)) {
            $updateStmt = $conn->prepare("UPDATE user_accounts SET status = ? WHERE id = ?");
            $updateStmt->execute([$newStatus, $userId]);
            $_SESSION['success_message'] = "Status updated successfully";
        } else {
            $_SESSION['error_message'] = "Invalid status value";
        }
        header("Location: users.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Update failed: " . $e->getMessage();
        header("Location: users.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User Details</title>
    <link rel="stylesheet" href="css/view_users.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="assets/Southside.png">


</head>
<body>
<?php 
    $pageTitle = "User Details";
    include 'header.php';
    include 'sidebar.php';
?>

<div class="main-container mt-5">
    <div class="container">
        <button onclick="history.back()" class="btn btn-secondary mb-3" style="font-size: 1.25rem;">
            <i class="fas fa-arrow-left"></i> Back
        </button>
        <h2 class="text-center mb-4">User Details</h2>

        <div class="row">
            <!-- Profile Picture and Valid ID Column -->
            <div class="col-md-4 text-center">
                <!-- Profile Picture -->
                <div class="profile-section">
                    <h5>Profile Picture</h5>
                    <?php if (!empty($userData['user_profile_picture'])): ?>
                        <img src="<?php echo htmlspecialchars($userData['user_profile_picture']); ?>" 
                             class="img-thumbnail zoomable" 
                             alt="Profile Picture"
                             onclick="document.getElementById('imageModal').style.display='block'; document.getElementById('modalImage').src=this.src;">
                    <?php else: ?>
                        <div class="alert alert-info">No profile picture uploaded</div>
                    <?php endif; ?>
                </div>

                <!-- Valid ID -->
                <div class="id-section">
                    <h5>Valid ID</h5>
                    <div class="row">
                        <!-- Front of ID -->
                        <div class="col-md-6 mb-3">
                            <h6>Front</h6>
                            <?php if (!empty($userData['user_valid_id'])): ?>
                                <img src="<?php echo htmlspecialchars($userData['user_valid_id']); ?>" 
                                    class="img-thumbnail zoomable" 
                                    alt="Valid ID (Front)"
                                    onclick="document.getElementById('imageModal').style.display='block'; document.getElementById('modalImage').src=this.src;">
                            <?php else: ?>
                                <div class="alert alert-info">No front ID uploaded</div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Back of ID -->
                        <div class="col-md-6 mb-3">
                            <h6>Back</h6>
                            <?php if (!empty($userData['user_valid_id_back'])): ?>
                                <img src="<?php echo htmlspecialchars($userData['user_valid_id_back']); ?>" 
                                    class="img-thumbnail zoomable" 
                                    alt="Valid ID (Back)"
                                    onclick="document.getElementById('imageModal').style.display='block'; document.getElementById('modalImage').src=this.src;">
                            <?php else: ?>
                                <div class="alert alert-info">No back ID uploaded</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Details Column -->
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Full Name:</strong> 
                            <?php echo htmlspecialchars($userData['firstName'] . ' ' . $userData['lastName']); ?>
                        </p>
                        <p><strong>Username:</strong> 
                            <?php echo htmlspecialchars($userData['username']); ?>
                        </p>
                        <p><strong>Age:</strong> 
                            <?php
                            $birthDate = new DateTime($userData['birthday']);
                            $currentDate = new DateTime();
                            $age = $currentDate->diff($birthDate)->y; // Calculate the difference in years
                            echo htmlspecialchars($age);
                            ?>
                        </p>
                        <p><strong>Gender:</strong> 
                            <?php echo htmlspecialchars(ucfirst($userData['gender'])); ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Address:</strong> 
                            <?php 
                                $address = $userData['adrHouseNo'] . ' ' . $userData['adrStreet'] . ', Zone ' . $userData['adrZone'];
                                echo htmlspecialchars($address);
                            ?>
                        </p>
                        <p><strong>Birthday:</strong> 
                            <?php echo date('F d, Y', strtotime($userData['birthday'])); ?>
                        </p>
                        <p><strong>Last Active:</strong> 
                            <?php echo $userData['last_active'] ? date('F d, Y g:i A', strtotime($userData['last_active'])) : 'Never'; ?>
                        </p>
                        <p><strong>Status:</strong> 
                            <form method="POST" id="statusForm">
                                <select id="statusSelect" name="status" class="form-select">
                                    <option value="pending" <?php echo ($userData['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="verified" <?php echo ($userData['status'] === 'verified') ? 'selected' : ''; ?>>Verified</option>
                                    <option value="rejected" <?php echo ($userData['status'] === 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                            </form>
                        </p>
                        <div class="text-start mt-4">
                    <button class="btn-save" onclick="document.getElementById('confirmModal').style.display='block'">
                        Save Changes
                    </button>
                </div>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5>Image Preview</h5>
            <i class="fas fa-times close-modal" onclick="document.getElementById('imageModal').style.display='none'"></i>
        </div>
        <div class="modal-body text-center p-0">
            <img id="modalImage" src="" class="img-fluid" alt="Image Preview">
        </div>
    </div>
</div>

<!-- Custom Confirmation Modal -->
<div id="confirmModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5>Confirm Changes</h5>
            <i class="fas fa-times close-modal" onclick="document.getElementById('confirmModal').style.display='none'"></i>
        </div>
        <div class="custom-modal-body">
            Are you sure you want to update this user's status?
        </div>
        <div class="custom-modal-footer">
            <button class="btn-cancel" onclick="document.getElementById('confirmModal').style.display='none'">Cancel</button>
            <button class="btn-confirm" onclick="document.getElementById('statusForm').submit();">Confirm</button>
        </div>
    </div>
</div>

<script>
    // Close modals when clicking outside
    window.onclick = function(event) {
        const confirmModal = document.getElementById('confirmModal');
        const imageModal = document.getElementById('imageModal');
        if (event.target == confirmModal) {
            confirmModal.style.display = 'none';
        }
        if (event.target == imageModal) {
            imageModal.style.display = 'none';
        }
    }
</script>
</body>
</html>
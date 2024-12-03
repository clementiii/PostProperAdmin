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
    <link rel="stylesheet" href="css/users.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="icon" type="image/png" href="assets/Southside.png">
    <style>
        .custom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1050;
        }

        .custom-modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 500px;
            position: relative;
        }

        .custom-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .custom-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
        }

        .close-modal {
            cursor: pointer;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .btn-cancel {
            padding: 5px 15px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-confirm {
            padding: 5px 15px;
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .user-details {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .profile-picture {
            max-width: 200px;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        .detail-row {
            margin-bottom: 15px;
        }
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
        .id-section, .profile-section {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
    </style>
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
                    <?php if (!empty($userData['user_valid_id'])): ?>
                        <img src="<?php echo htmlspecialchars($userData['user_valid_id']); ?>" 
                             class="img-thumbnail zoomable" 
                             alt="Valid ID"
                             onclick="document.getElementById('imageModal').style.display='block'; document.getElementById('modalImage').src=this.src;">
                    <?php else: ?>
                        <div class="alert alert-info">No valid ID uploaded</div>
                    <?php endif; ?>
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
                            <?php echo htmlspecialchars($userData['age']); ?>
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
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button class="btn btn-primary" onclick="document.getElementById('confirmModal').style.display='block'">
                        Save Changes
                    </button>
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
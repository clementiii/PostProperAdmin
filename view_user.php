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
    <title>View User Details | Post Proper Southside</title>
    <link rel="stylesheet" href="css/view_users.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/Southside.png">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <div class="main-container">
        <div class="container">
            <!-- Header Section -->
            <div class="header-section">
                <button onclick="history.back()" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back</span>
                </button>
                <h1 class="page-title">User Details</h1>
            </div>

            <!-- Main Content Grid -->
            <div class="content-grid">
                <!-- Left Column - Profile & ID -->
                <div class="profile-column">
                    <!-- Profile Section -->
                    <div class="profile-section">
                        <div class="section-header">
                            <i class="fas fa-user"></i>
                            <h2>Profile Picture</h2>
                        </div>
                        <div class="profile-picture-container">
                            <?php if (!empty($userData['user_profile_picture'])): ?>
                                <img src="<?php echo htmlspecialchars($userData['user_profile_picture']); ?>" 
                                     alt="Profile Picture"
                                     class="profile-image"
                                     onclick="openImageModal(this.src)">
                            <?php else: ?>
                                <div class="no-profile-message">
                                    <i class="fas fa-camera"></i>
                                    <p>No profile picture uploaded</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- ID Section -->
                    <div class="id-section">
                        <div class="section-header">
                            <i class="fas fa-id-card"></i>
                            <h2>Valid ID</h2>
                        </div>
                        <div class="id-grid">
                            <!-- Front ID -->
                            <div class="id-container">
                                <h3>Front</h3>
                                <?php if (!empty($userData['user_valid_id'])): ?>
                                    <div class="id-image-wrapper" onclick="openImageModal('<?php echo htmlspecialchars($userData['user_valid_id']); ?>')">
                                        <img src="<?php echo htmlspecialchars($userData['user_valid_id']); ?>" 
                                             alt="Valid ID Front"
                                             class="id-image">
                                        <div class="image-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="no-id-placeholder">
                                        <i class="fas fa-id-card"></i>
                                        <p>No front ID uploaded</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Back ID -->
                            <div class="id-container">
                                <h3>Back</h3>
                                <?php if (!empty($userData['user_valid_id_back'])): ?>
                                    <div class="id-image-wrapper" onclick="openImageModal('<?php echo htmlspecialchars($userData['user_valid_id_back']); ?>')">
                                        <img src="<?php echo htmlspecialchars($userData['user_valid_id_back']); ?>" 
                                             alt="Valid ID Back"
                                             class="id-image">
                                        <div class="image-overlay">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="no-id-placeholder">
                                        <i class="fas fa-id-card"></i>
                                        <p>No back ID uploaded</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - User Details -->
                <div class="details-column">
                    <div class="details-card">
                        <div class="details-grid">
                            <!-- Personal Information Section -->
                            <div class="info-section">
                                <h2>Personal Information</h2>
                                
                                <div class="info-group">
                                    <label>Full Name</label>
                                    <div class="info-value">
                                        <i class="fas fa-user"></i>
                                        <?php echo htmlspecialchars($userData['firstName'] . ' ' . $userData['lastName']); ?>
                                    </div>
                                </div>

                                <div class="info-group">
                                    <label>Username</label>
                                    <div class="info-value">
                                        <i class="fas fa-at"></i>
                                        <?php echo htmlspecialchars($userData['username']); ?>
                                    </div>
                                </div>

                                <div class="info-group">
                                    <label>Address</label>
                                    <div class="info-value">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?php 
                                            $address = $userData['adrHouseNo'] . ' ' . $userData['adrStreet'] . ', Zone ' . $userData['adrZone'];
                                            echo htmlspecialchars($address);
                                        ?>
                                    </div>
                                </div>

                                <div class="info-group">
                                    <label>Birthday</label>
                                    <div class="info-value">
                                        <i class="fas fa-birthday-cake"></i>
                                        <?php echo date('F d, Y', strtotime($userData['birthday'])); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Information Section -->
                            <div class="info-section">
                                <h2>Account Information</h2>
                                
                                <div class="info-group">
                                    <label>Last Active</label>
                                    <div class="info-value">
                                        <i class="fas fa-clock"></i>
                                        <?php echo $userData['last_active'] ? date('F d, Y g:i A', strtotime($userData['last_active'])) : 'Never'; ?>
                                    </div>
                                </div>

                                <div class="info-group">
                                    <label>Account Status</label>
                                    <form method="POST" id="statusForm">
                                        <div class="status-select-wrapper">
                                            <select name="status" class="status-select" id="statusSelect">
                                                <option value="pending" <?php echo ($userData['status'] === 'pending') ? 'selected' : ''; ?>>
                                                    Pending
                                                </option>
                                                <option value="verified" <?php echo ($userData['status'] === 'verified') ? 'selected' : ''; ?>>
                                                    Verified
                                                </option>
                                                <option value="rejected" <?php echo ($userData['status'] === 'rejected') ? 'selected' : ''; ?>>
                                                    Rejected
                                                </option>
                                            </select>
                                            <i class="fas fa-chevron-down select-icon"></i>
                                        </div>
                                    </form>
                                </div>

                                <div class="button-container">
                                    <button class="btn-save" onclick="confirmUpdate()">
                                        <i class="fas fa-save"></i>
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Image Preview</h3>
                <button class="close-modal" onclick="closeImageModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" alt="Preview">
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirm Changes</h3>
                <button class="close-modal" onclick="closeConfirmModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to update this user's status?</p>
                <div class="modal-buttons">
                    <button class="btn-cancel" onclick="closeConfirmModal()">Cancel</button>
                    <button class="btn-confirm" onclick="submitForm()">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Open image modal
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.add('show');
        }

        // Close image modal
        function closeImageModal() {
            document.getElementById('imageModal').classList.remove('show');
        }

        // Open confirmation modal
        function confirmUpdate() {
            document.getElementById('confirmModal').classList.add('show');
        }

        // Close confirmation modal
        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.remove('show');
        }

        // Submit the form
        function submitForm() {
            document.getElementById('statusForm').submit();
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('show');
            }
        }
    </script>
</body>
</html>
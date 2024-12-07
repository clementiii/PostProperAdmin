<title>Admin Profile</title>
<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['admin_id'])) {
    header("Location: splash.php");
    exit;
}

include 'db.php';
$pageTitle = "Admin Profile";
include 'header.php';
include 'sidebar.php';

$adminId = isset($_GET['id']) ? (int)$_GET['id'] : $_SESSION['admin_id'];

$admin = null;
if ($adminId > 0) {
    try {
        $sql = "SELECT id, name, username, password, profile_picture FROM admin_accounts WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST['username'] ?? null;
    $oldPassword = $_POST['old_password'] ?? null;
    $newPassword = $_POST['new_password'] ?? null;
    $confirmNewPassword = $_POST['confirm_new_password'] ?? null;

    $passwordChanged = false;
    $usernameChanged = false;
    $profilePictureChanged = false;

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/profile_pictures/";
        $fileName = time() . '_' . basename($_FILES['profile_picture']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($fileType), $allowedTypes)) {
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
                $updatePictureSql = "UPDATE admin_accounts SET profile_picture = ? WHERE id = ?";
                $stmt = $conn->prepare($updatePictureSql);
                $stmt->execute([$targetFilePath, $adminId]);
                $profilePictureChanged = true;
            } else {
                echo "<script>alert('Failed to upload profile picture.');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type. Please upload an image.');</script>";
        }
    }

    try {
        if ($newUsername && $newUsername !== $admin['username']) {
            $updateUsernameSql = "UPDATE admin_accounts SET username = ? WHERE id = ?";
            $stmt = $conn->prepare($updateUsernameSql);
            $stmt->execute([$newUsername, $adminId]);
            $usernameChanged = true;
        }

        if ($oldPassword && $newPassword && $confirmNewPassword) {
            if ($admin['password'] === $oldPassword) {
                if ($newPassword === $confirmNewPassword) {
                    $updatePasswordSql = "UPDATE admin_accounts SET password = ? WHERE id = ?";
                    $stmt = $conn->prepare($updatePasswordSql);
                    $stmt->execute([$newPassword, $adminId]);
                    $passwordChanged = true;
                } else {
                    echo "<script>alert('New Password and Confirm Password do not match.');</script>";
                }
            } else {
                echo "<script>alert('Incorrect old password.');</script>";
            }
        }

        if ($usernameChanged || $passwordChanged || $profilePictureChanged) {
            echo "<script>
                    alert('Profile updated successfully.');
                    window.location.href = 'admin_profile.php';
                  </script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error updating profile: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="css/AdminProfile.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="assets/Southside.png">
</head>

<body>
    <div class="content-wrapper">
        <div class="profile-container">
            <div class="profile-card">
                <div class="profile-header">
                    <img src="<?php echo !empty($admin['profile_picture']) ? $admin['profile_picture'] : 'assets/profile.jpg'; ?>" 
                         alt="Admin Profile" 
                         class="profile-image" 
                         id="profilePicturePreview">
                    <h2 class="admin-name"><?php echo htmlspecialchars($admin['name']); ?></h2>
                    <p class="admin-role">Administrator</p>
                </div>

                <form id="profileForm" method="POST" action="" enctype="multipart/form-data">
                    <div class="form-section">
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" 
                                   value="<?php echo htmlspecialchars($admin['name']); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" 
                                   value="<?php echo htmlspecialchars($admin['username']); ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="old_password" class="form-control" disabled>
                        </div>

                        <div class="additional-fields d-none" id="newPasswordFields">
                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <input type="password" name="new_password" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="confirm_new_password" class="form-control">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Profile Picture</label>
                                <input type="file" name="profile_picture" class="form-control" accept="image/*">
                            </div>
                        </div>

                        <div class="button-group">
                            <button type="button" class="btn-edit" onclick="enableEditing()">
                                <i class="fas fa-edit"></i> Edit Profile
                            </button>
                            <button type="submit" class="btn-save d-none">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let isEditing = false;

        function enableEditing() {
            isEditing = true;
            document.querySelectorAll('.form-control').forEach(field => {
                if (field.type !== 'file') {
                    field.disabled = false;
                }
            });
            document.getElementById('newPasswordFields').classList.remove('d-none');
            document.querySelector('.btn-edit').classList.add('d-none');
            document.querySelector('.btn-save').classList.remove('d-none');
        }

        // Preview profile picture
        document.querySelector('input[type="file"]').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePicturePreview').src = e.target.result;
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
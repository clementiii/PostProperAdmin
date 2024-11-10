<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['admin_id'])) {
    header("Location: splash.php");
    exit;
}

include 'db.php';

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['old_password'], $_POST['new_password'], $_POST['confirm_new_password'], $_POST['username'])) {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmNewPassword = $_POST['confirm_new_password'];
    $newUsername = $_POST['username'];

    // Flags to track what changes were made
    $passwordChanged = false;
    $usernameChanged = false;

    try {
        // Fetch current admin data
        $checkSql = "SELECT username, password FROM admin_accounts WHERE id = ?";
        $stmt = $conn->prepare($checkSql);
        $stmt->execute([$adminId]);
        $currentAdmin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the username has changed
        if ($currentAdmin['username'] !== $newUsername) {
            $updateUsernameSql = "UPDATE admin_accounts SET username = ? WHERE id = ?";
            $stmt = $conn->prepare($updateUsernameSql);
            $stmt->execute([$newUsername, $adminId]);
            $usernameChanged = true;
        }

        // Check if a password change is requested
        if (!empty($newPassword) && !empty($oldPassword)) {
            // Verify old password before updating
            if ($currentAdmin && $currentAdmin['password'] === $oldPassword) {
                // Check if the new password matches confirmation
                if ($newPassword === $confirmNewPassword) {
                    $updatePasswordSql = "UPDATE admin_accounts SET password = ? WHERE id = ?";
                    $stmt = $conn->prepare($updatePasswordSql);
                    $stmt->execute([$newPassword, $adminId]);
                    $passwordChanged = true;
                } else {
                    echo "<script>alert('New Password and Confirm New Password do not match.');</script>";
                }
            } else {
                echo "<script>alert('Incorrect old password. Please try again.');</script>";
            }
        }

        // Display appropriate success message and redirect
        if ($usernameChanged && $passwordChanged) {
            echo "<script>
                    alert('Username and password changed successfully.');
                    window.location.href = 'admin_staff.php';
                  </script>";
        } elseif ($usernameChanged) {
            echo "<script>
                    alert('Username changed successfully.');
                    window.location.href = 'admin_staff.php';
                  </script>";
        } elseif ($passwordChanged) {
            echo "<script>
                    alert('Password changed successfully.');
                    window.location.href = 'admin_staff.php';
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="profile-card p-4 position-relative">
            <!-- Back Button -->
            <button onclick="goBack()" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </button>

            <div class="text-center mb-3">
                <img src="<?php echo !empty($admin['profile_picture']) ? $admin['profile_picture'] : 'assets/profile.jpg'; ?>" alt="Admin Profile" class="profile-image rounded-circle">
                <p class="admin-title">ADMIN</p>
            </div>
            <h2 class="section-title">Admin Information</h2>
            <form id="profileForm" method="POST" action="">
                <div class="mb-3 text-start">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control input-field" value="<?php echo htmlspecialchars($admin['name']); ?>" disabled>
                </div>
                <div class="mb-3 text-start">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control input-field" value="<?php echo htmlspecialchars($admin['username']); ?>" disabled>
                </div>
                <div class="mb-3 text-start">
                    <label class="form-label">Password</label>
                    <input type="password" name="old_password" class="form-control input-field" disabled>
                </div>
                <div class="mb-3 text-start d-none" id="newPasswordFields">
                    <label class="form-label mt-2">New Password</label>
                    <input type="password" name="new_password" class="form-control input-field">
                    <label class="form-label mt-2">Confirm New Password</label>
                    <input type="password" name="confirm_new_password" class="form-control input-field">
                </div>
                <button type="button" class="action-btn edit-btn" onclick="enableEditing()">Edit Profile</button>
                <button type="submit" class="action-btn save-btn d-none mt-2">Save Changes</button>
            </form>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back(); // Navigates to the previous page
        }

        function enableEditing() {
            document.querySelectorAll('.input-field').forEach(field => field.disabled = false);
            document.getElementById('newPasswordFields').classList.remove('d-none');
            document.querySelector('.edit-btn').classList.add('d-none');
            document.querySelector('.save-btn').classList.remove('d-none');
            document.querySelector('.profile-card').style.maxHeight = '90vh';
        }
    </script>
</body>
</html>


        
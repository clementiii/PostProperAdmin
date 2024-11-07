<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['admin_id'])) {
    header("Location: splash.php"); // Redirect to login page if not logged in or admin ID is missing
    exit;
}

include 'db.php'; // Make sure this file connects to your `pps_barangay_system` database

// Check if we are editing another admin's profile or the current logged-in admin's profile
$adminId = isset($_GET['id']) ? (int)$_GET['id'] : $_SESSION['admin_id']; // Use session ID if no URL parameter

// Fetch the admin details from the database
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

// Handle form submission (profile update)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password'])) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Password and Confirm Password do not match.');</script>";
    } else {
        // Process and save the updated information
        try {
            $updateSql = "UPDATE admin_accounts SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($updateSql);
            $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $adminId]); // Hash password before saving

            echo "<script>alert('Profile updated successfully.');</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Error updating profile: " . $e->getMessage() . "');</script>";
        }
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="profile-card p-4">
            <div class="text-center mb-3">
                <!-- Display the profile picture, use a default if not available -->
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
                    <input type="password" name="password" class="form-control input-field" disabled>
                </div>
                <div class="mb-3 text-start d-none" id="confirmPasswordField">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control input-field">
                </div>
                <button type="button" class="action-btn edit-btn" onclick="enableEditing()">Edit Profile</button>
                <button type="submit" class="action-btn save-btn d-none mt-2">Save Changes</button>
            </form>
        </div>
    </div>

    <script>
        function enableEditing() {
            document.querySelectorAll('.input-field').forEach(field => {
                field.disabled = false;
            });
            document.getElementById('confirmPasswordField').classList.remove('d-none');
            document.querySelector('.edit-btn').classList.add('d-none');
            document.querySelector('.save-btn').classList.remove('d-none');
        }
    </script>
</body>
</html>

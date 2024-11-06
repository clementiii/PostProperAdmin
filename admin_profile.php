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

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

if ($password !== $confirm_password) {
echo "<script>alert('Password and Confirm Password do not match.');</script>";
} else {
// Process and save the updated information as needed
echo "<script>alert('Profile updated successfully.');</script>";
}
}
?>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="profile-card p-4">
            <div class="text-center mb-3">
                <img src="assets/profile.jpg" alt="Admin Profile" class="profile-image rounded-circle">
                <p class="admin-title">ADMIN</p>
            </div>
            <h2 class="section-title">Admin Information</h2>
            <form id="profileForm" method="POST" action="">
                <div class="mb-3 text-start">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control input-field" value="Leo Administrator" disabled>
                </div>
                <div class="mb-3 text-start">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control input-field" value="admin123456789" disabled>
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

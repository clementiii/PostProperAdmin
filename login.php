<?php
session_start();
include 'db.php'; // Ensure this file is correctly set up to connect to your `pps_barangay_system` database

// Initialize error message
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Prepare the SQL statement to fetch the password based on the entered username
        $sql = "SELECT id, name, password FROM admin_accounts WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if username exists and password matches
        if ($result && $password == $result['password']) {
            // Successful login
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['admin_id'] = $result['id']; // Store admin ID
            $_SESSION['name'] = $result['name'];   // Store admin name

            header("Location: dashboard.php");
            exit;
        } else {
            // Either username not found or password mismatch
            $error = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        // Handle any database errors
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/LoginStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="logo-container">
    <div class="logo-section">
        <img src="assets/Southside.png" alt="Logo">
        <h1>Post Proper Southside</h1>
    </div>
</div>

<div class="login-container">
    <div class="login-box">
        <h2>ADMIN</h2>
        <form action="" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input class="textfield" type="text" id="username" name="username" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <div style="position: relative;">
                    <input class="textfield" type="password" id="password" name="password" required>
                    <i class="fas fa-eye toggle-password" onclick="togglePassword()" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                </div>
            </div>

            <button type="submit">Login</button>
        </form>

        <?php
        if ($error) {
            echo '<p class="error">' . $error . '</p>';
        }
        ?>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.querySelector('.toggle-password');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>

</body>
</html>

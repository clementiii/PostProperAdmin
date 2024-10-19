<?php
session_start();

// Sample hardcoded credentials (replace this with database validation in a real application)
$valid_username = "admin";
$valid_password = "admin123";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == $valid_username && $password == $valid_password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php"); // Redirect to a protected page
        exit;
    } else {
        $error = "Invalid username or password";
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
</head>
<body>

<div class = "logo-container">
    <div class = "logo-section">
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
                <input type="text" id="username" name="username" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">Login</button>
        </form>

        <?php
        if (isset($error)) {
            echo '<p class="error">' . $error . '</p>';
        }
        ?>
    </div>
</div>

</body>
</html>

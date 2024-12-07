<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="css/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" type="image/png" href="assets/Southside.png">
</head>
<body>  
<?php
// Database connection (make sure to include db.php)
require 'db.php';

// header.php
$pageTitle = isset($pageTitle) ? $pageTitle : "Default Title";

// Make sure the admin is logged in before showing the profile link
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['admin_id'])) {
    $adminId = $_SESSION['admin_id'];
    $profileLink = "admin_profile.php";

    try {
        $sql = "SELECT profile_picture FROM admin_accounts WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        $profilePicture = !empty($admin['profile_picture']) ? $admin['profile_picture'] : 'assets/profile.jpg';
    } catch (PDOException $e) {
        echo "Error fetching profile picture: " . $e->getMessage();
        $profilePicture = 'assets/profile.jpg';
    }
} else {
    $profileLink = "splash.php";
    $profilePicture = 'assets/profile.jpg';
}
?>

<div class="header-container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <div class="account">
        <a href="<?php echo $profileLink; ?>" class="account-link">
            <img src="<?php echo $profilePicture; ?>" alt="Admin Profile" class="img-fluid rounded-circle account-icon">
        </a>
    </div>
</div>
</body>
</html>
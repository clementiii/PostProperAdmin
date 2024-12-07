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
    $adminId = $_SESSION['admin_id'];  // Use correct session variable for admin ID
    $profileLink = "admin_profile.php"; // Directly link to admin_profile.php (since the ID is in session)

    // Fetch the admin's profile picture from the database
    try {
        $sql = "SELECT profile_picture FROM admin_accounts WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set default profile picture if not found in database
        $profilePicture = !empty($admin['profile_picture']) ? $admin['profile_picture'] : 'assets/profile.jpg';
    } catch (PDOException $e) {
        echo "Error fetching profile picture: " . $e->getMessage();
        // Set fallback profile picture if there's an error
        $profilePicture = 'assets/profile.jpg';
    }
} else {
    $profileLink = "splash.php"; // Redirect to login if not logged in or session admin ID is missing
    $profilePicture = 'assets/profile.jpg'; // Default picture for non-logged-in users
}
?>

<div class="header-container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <div class="account">
        <!-- Redirect to the logged-in admin's profile page -->
        <a href="<?php echo $profileLink; ?>" class="account-link">
            <img src="<?php echo $profilePicture; ?>" alt="Admin Profile" class="img-fluid rounded-circle account-icon">
        </a>
    </div>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <link rel="stylesheet" href="css/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<style>
    
</style>
<body>  
<?php
// header.php
$pageTitle = isset($pageTitle) ? $pageTitle : "Default Title";
?>

<div class="header-container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
    <div class="account">
        <a href="admin_profile.php" class="account-link">
            <img src="assets/profile.jpg" alt="Admin Profile" class="img-fluid rounded-circle account-icon">
        </a>
    </div>
</div>
</body>
</html>

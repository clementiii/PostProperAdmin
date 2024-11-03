<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <link rel="stylesheet" href="css/header.css">
    <!-- Other CSS files -->
</head>
<body>  
<?php
// header.php
$pageTitle = isset($pageTitle) ? $pageTitle : "Default Title"; // Default if $pageTitle is not set
?>

<div class="header-container">
    <h1 class="page-title"><?php echo $pageTitle; ?></h1>
</div>
</body>
</html>


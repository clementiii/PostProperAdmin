<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php");
    exit;
}

include 'db.php';

// Query the database to get all admin users
try {
    $sql = "SELECT id, name FROM admin_accounts";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$currentAdminId = $_SESSION['admin_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Staff</title>
    
    <link rel="stylesheet" href="css/AdminStaff.css">
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <link rel="icon" type="image/png" href="assets/Southside.png">
</head>
<body>
    
<?php 
    $pageTitle = "Admin Staffs";
    include 'header.php';
    include 'sidebar.php'; 
?>

<div class="main-content">
    <div class="container">
        <div class="custom-table">
            <table class="table table-borderless mb-0">
                <thead>
                    <tr>
                        <th>Admin Name</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($admins)) {
                        foreach ($admins as $admin) {
                            $firstLetter = strtoupper(substr($admin['name'], 0, 1));
                            echo "<tr>";
                            echo "<td>
                                    <div class='admin-name'>
                                        <div class='admin-avatar'>$firstLetter</div>
                                        " . htmlspecialchars($admin['name']) . "
                                    </div>
                                  </td>";
                            echo "<td class='text-end'>";
                            
                            if ($admin['id'] == $currentAdminId) {
                                echo "<a href='admin_profile.php?id=" . $admin['id'] . "' class='btn btn-edit me-2'>
                                        <i class='fas fa-edit'></i> Edit
                                      </a>";
                                echo "<a href='delete_admin.php?id=" . $admin['id'] . "' class='btn btn-delete' 
                                        onclick='return confirm(\"Are you sure you want to delete this admin?\");'>
                                        <i class='fas fa-trash'></i> Delete
                                      </a>";
                            } else {
                                echo "<button class='btn btn-edit me-2' disabled>
                                        <i class='fas fa-edit'></i> Edit
                                      </button>";
                                echo "<button class='btn btn-delete' disabled>
                                        <i class='fas fa-trash'></i> Delete
                                      </button>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2' class='text-center'>No admin users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.jshttps://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
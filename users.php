<?php
session_start();
include 'db.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php"); // Redirect to the login page if not logged in
    exit;
}

// Fetch data from the user_accounts table
try {
    $sql = "SELECT id, firstName, lastName, age, gender, adrHouseNo, adrZone, adrStreet, birthday FROM user_accounts";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="css/users.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    
</head>
<body>
<?php 
    $pageTitle = "Resident Users";
    include 'header.php';
    include 'sidebar.php';
?>

<div class="main-content">
    <div class="container" style="margin-top: 5px;">
    
        <!-- User Statistics Boxes -->
        <div class="row justify-content-center" style="margin-bottom: 45px;">
            <div class="col-md-4">
                <div class="stat-box total-reports bg-primary text-center py-3">
                    <h4>Registered Residents</h4>
                    <div class="stat-number">200</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box pending bg-success text-center py-3">
                    <h4>Active Users</h4>
                    <div class="stat-number">130</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box resolved bg-secondary text-center py-3">
                    <h4>Inactive Users</h4>
                    <div class="stat-number">70</div>
                </div>
            </div>
        </div>

        <!-- User Table -->
        <div class="table-responsive" style="margin-left:">
            <table class="table table-bordered text-center align-middle">
                <thead style="background-color: #D9D9E6;">
                    <tr>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Address</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr style="background-color: <?php echo ($user['id'] % 2 == 0) ? '#FFFFFF' : '#F5F5FB'; ?>;">
                        <td><?php echo htmlspecialchars($user['lastName']); ?></td>
                        <td><?php echo htmlspecialchars($user['firstName']); ?></td>
                        <td>
                            <?php 
                                echo htmlspecialchars($user['adrHouseNo']) . " " . 
                                     htmlspecialchars($user['adrStreet']) . " " . 
                                     htmlspecialchars($user['adrZone']); 
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($user['age']); ?></td>
                        <td><?php echo htmlspecialchars($user['gender']); ?></td>
                        <td><?php echo date('m/d/Y', strtotime($user['birthday'])); ?></td>
                        <td><button class="btn btn-danger">Delete</button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>

<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php");
    exit;
}

// Set the inactive threshold (e.g., 30 days)
$inactiveThreshold = date('Y-m-d H:i:s', strtotime('-30 days'));

try {
    // Fetch basic user data
    $sql = "SELECT id, firstName, lastName, age, gender, adrHouseNo, adrZone, adrStreet, birthday, status FROM user_accounts";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Count total registered users
    $registeredResidentsQuery = "SELECT COUNT(*) AS count FROM user_accounts";
    $registeredResidentsResult = $conn->query($registeredResidentsQuery)->fetch(PDO::FETCH_ASSOC);
    $registeredResidentsCount = $registeredResidentsResult['count'];

    // Count active users (users who have been active within the last 30 days)
    $activeUsersQuery = "SELECT COUNT(*) AS count FROM user_accounts 
                        WHERE last_active >= :threshold";
    $stmt = $conn->prepare($activeUsersQuery);
    $stmt->bindParam(':threshold', $inactiveThreshold);
    $stmt->execute();
    $activeUsersResult = $stmt->fetch(PDO::FETCH_ASSOC);
    $activeUsersCount = $activeUsersResult['count'];

    // Calculate inactive users
    $inactiveUsersCount = $registeredResidentsCount - $activeUsersCount;

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
    <link rel="icon" type="image/png" href="assets/Southside.png">
</head>
<body>
<?php 
    $pageTitle = "Resident Users";
    include 'header.php';
    include 'sidebar.php';
?>

<div class="main-content">
    <div class="container mt-4 px-4">
        <!-- User Statistics Boxes -->
        <div class="row justify-content-center mb-5" style="max-width: 100vw; margin: 0 auto;">
            <div class="col-md-4 px-4">
                <div class="stat-box total-resident text-center py-3">
                    <h4>Registered Residents</h4>
                    <div class="stat-number"><?php echo $registeredResidentsCount; ?></div>
                </div>
            </div>
            <div class="col-md-4 px-4">
                <div class="stat-box active-user text-center py-3">
                    <h4>Active Users</h4>
                    <div class="stat-number"><?php echo $activeUsersCount; ?></div>
                </div>
            </div>
            <div class="col-md-4 px-4">
                <div class="stat-box inactive-user text-center py-3">
                    <h4>Inactive Users</h4>
                    <div class="stat-number"><?php echo $inactiveUsersCount; ?></div>
                </div>
            </div>
        </div>

        <!-- User Table -->
        <div class="table-responsive" style="max-width: 100vw; margin-left: -1rem;">
            <table class="table table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Address</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr style="background-color: <?php echo ($user['id'] % 2 == 0) ? '#F5F5FB' : '#FFFFFF'; ?>;">
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
                        <td>
                            <span class="badge <?php 
                                $statusClass = '';
                                if ($user['status'] === 'verified') {
                                    $statusClass = 'bg-success';
                                } elseif ($user['status'] === 'rejected') {
                                    $statusClass = 'bg-danger';
                                } else {
                                    $statusClass = 'bg-warning';
                                }
                                echo $statusClass;
                            ?>">
                                <?php echo ucfirst($user['status'] ?? 'pending'); ?>
                            </span>
                        </td>
                        <td>
                            <a href="view_user.php?id=<?php echo $user['id']; ?>" class="btn btn-primary btn-sm">View</a>
                            <?php if(!isset($user['status']) || $user['status'] === 'pending'): ?>
                                <a href="verify_user.php?id=<?php echo $user['id']; ?>" class="btn btn-success btn-sm">Verify</a>
                            <?php endif; ?>
                            <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $user['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="deleteConfirmationModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5 class="modal-title">Confirm Deletion</h5>
            <i class="fas fa-times close-modal"></i>
        </div>
        <div class="custom-modal-body">
            Are you sure you want to delete this user? This action cannot be undone.
        </div>
        <div class="custom-modal-footer">
            <button class="btn btn-secondary close-modal">Cancel</button>
            <button class="btn btn-danger" onclick="deleteConfirmed()">Delete</button>
        </div>
    </div>
</div>


<script>
let userIdToDelete;

function confirmDelete(userId) {
    userIdToDelete = userId;
    document.getElementById('deleteConfirmationModal').style.display = 'block';
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function deleteConfirmed() {
    window.location.href = 'deleteUser.php?id=' + userIdToDelete;
}

// Close modal when clicking the X button or Cancel button
document.querySelectorAll('.close-modal').forEach(button => {
    button.onclick = function() {
        document.getElementById('deleteConfirmationModal').style.display = 'none';
        document.body.style.overflow = 'auto'; // Restore scrolling
    }
});

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('deleteConfirmationModal');
    if (event.target == modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Restore scrolling
    }
}

// Table sorting functionality
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('th').forEach((header, index) => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', () => {
            sortTable(index);
        });
    });
});

function sortTable(columnIndex) {
    const table = document.querySelector('table tbody');
    const rows = Array.from(table.rows);

    const isAscending = table.getAttribute('data-sort-asc') === 'true' ? false : true;
    table.setAttribute('data-sort-asc', isAscending);

    rows.sort((a, b) => {
        const cellA = a.cells[columnIndex].textContent.trim();
        const cellB = b.cells[columnIndex].textContent.trim();

        if (columnIndex === 3) { // Age column (numeric sort)
            return isAscending ? cellA - cellB : cellB - cellA;
        } else {
            return isAscending 
                ? cellA.localeCompare(cellB) 
                : cellB.localeCompare(cellA);
        }
    });

    rows.forEach(row => table.appendChild(row));
}
</script>

</body>
</html>
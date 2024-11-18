<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php");
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

// Fetch count of registered residents
$registeredResidentsQuery = "SELECT COUNT(*) AS count FROM user_accounts";
$registeredResidentsResult = $conn->query($registeredResidentsQuery)->fetch(PDO::FETCH_ASSOC);
$registeredResidentsCount = $registeredResidentsResult['count'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="css/users.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <script>
        let userIdToDelete;

        function confirmDelete(userId) {
            userIdToDelete = userId;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            deleteModal.show();
        }

        function deleteConfirmed() {
            window.location.href = 'deleteUser.php?id=' + userIdToDelete;
        }
    </script>
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
                    <div class="stat-number">130</div>
                </div>
            </div>
            <div class="col-md-4 px-4">
                <div class="stat-box inactive-user text-center py-3">
                    <h4>Inactive Users</h4>
                    <div class="stat-number">70</div>
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
                            <button class="btn btn-danger" onclick="confirmDelete(<?php echo $user['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="deleteConfirmed()">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Add event listeners to header cells to trigger sorting
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

        // Append sorted rows to the table
        rows.forEach(row => table.appendChild(row));
    }
</script>
</body>
</html>

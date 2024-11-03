<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: splash.php"); // Redirect to the login page if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Staff</title>
    
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/AdminStaff.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <style>
        /* Custom Styles */
        body {
            font-family: 'Poppins', sans-serif;
        }
        .table th, .table td {
            text-align: center; /* Center text in table cells */
            vertical-align: middle;
        }
        /* Larger font for table header */
        .table thead th {
            font-size: 1.25rem; /* Relative font size */
            font-weight: 600;
        }
        /* Custom button size (responsive) */
        .btn-custom {
            display: block;
            width: 100%;
            max-width: 160px;
            height: 36px;
            font-size: 1rem;
            margin-bottom: 10px;
        }
        /* Center buttons in Action column */
        .action-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>
<body>
    
<?php 
    $pageTitle = "Admin Staff";
    include 'header.php';
    include 'sidebar.php'; 
?>

<div class="main-content">
    <div class="container">
        <table class="table table-bordered mt-4">
            <thead class="table-light">
                <tr>
                    <th scope="col">Admin Name</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Kiyosaki Hayabusa</td>
                    <td class="action-buttons">
                        <button class="btn btn-primary btn-custom" onclick="editAdmin('Kiyosaki Hayabusa')">Edit</button>
                        <button class="btn btn-danger btn-custom" onclick="deleteAdmin('Kiyosaki Hayabusa')">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>Rico Salvador</td>
                    <td class="action-buttons">
                        <button class="btn btn-primary btn-custom" onclick="editAdmin('Rico Salvador')">Edit</button>
                        <button class="btn btn-danger btn-custom" onclick="deleteAdmin('Rico Salvador')">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>Kathryn Abunda</td>
                    <td class="action-buttons">
                        <button class="btn btn-primary btn-custom" onclick="editAdmin('Kathryn Abunda')">Edit</button>
                        <button class="btn btn-danger btn-custom" onclick="deleteAdmin('Kathryn Abunda')">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>Roberto Del Rosario</td>
                    <td class="action-buttons">
                        <button class="btn btn-primary btn-custom" onclick="editAdmin('Roberto Del Rosario')">Edit</button>
                        <button class="btn btn-danger btn-custom" onclick="deleteAdmin('Roberto Del Rosario')">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    function editAdmin(name) {
        alert("Edit " + name);
        // Add your edit logic here
    }

    function deleteAdmin(name) {
        if (confirm("Are you sure you want to delete " + name + "?")) {
            alert(name + " deleted");
            // Add your delete logic here
        }
    }
</script>

</body>
</html>

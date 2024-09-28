<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #333;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            color: #fff;
        }
        .sidebar img {
            width: 80px;
            margin-bottom: 20px;
        }
        .sidebar h2 {
            margin-bottom: 20px;
        }
        .sidebar a {
            text-decoration: none;
            color: #fff;
            display: block;
            padding: 10px;
            margin-bottom: 10px;
        }
        .sidebar a:hover {
            background-color: #444;
        }
        .sidebar a.active {
            background-color: #666;
        }
        .logout {
            background-color: #f44336; /* Red background for the logout button */
            text-align: center;
            padding: 10px;
            margin-top: 10px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
        }
        .logout:hover {
            background-color: #d32f2f; /* Darker red on hover */
        }

        /* Main content */
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        /* Header */
        .header {
            background-color: #ddd;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
        }
        .profile {
            display: flex;
            align-items: center;
        }
        .profile img {
            width: 50px;
            border-radius: 50%;
            margin-left: 20px;
        }

        /* Dashboard content */
        .dashboard {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .dashboard .box {
            width: 30%;
            background-color: #f1f1f1;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 10px;
            min-width: 250px;
        }
        .dashboard .box:hover {
            background-color: #e1e1e1;
        }

        /* Table Section */
        .table-section {
            margin-top: 20px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            margin-top: 20px;
            table-layout: fixed;
        }
        th, td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        th {
            background-color: #f2f2f2;
        }
        td {
            word-wrap: break-word;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            .dashboard {
                flex-direction: column;
                align-items: center;
            }

            .dashboard .box {
                width: 80%;
                margin: 10px auto;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .profile img {
                margin-left: 0;
                margin-top: 10px;
            }
        }

        @media (max-width: 480px) {
            .table-section table {
                font-size: 14px;
            }
            .header h1 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?> 

<div class="main-content">
    <!-- Header -->
    <div class="header">
        <h1>Welcome, Admin</h1>
        <div class="profile">
            <span>J</span>
            <img src="path-to-profile-image.png" alt="Profile Picture">
        </div>
    </div>

    <!-- Dashboard boxes -->
    <div class="dashboard">
        <div class="box">
            <h3>Registered Residents</h3>
        </div>
        <div class="box">
            <h3>Document Requests</h3>
        </div>
        <div class="box">
            <h3>Incident Reports</h3>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-section">
        <table>
            <thead>
                <tr>
                    <th>Column 1</th>
                    <th>Column 2</th>
                    <th>Column 3</th>
                    <th>Column 4</th>
                    <th>Column 5</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Column 1</td>
                    <td>Column 2</td>
                    <td>Column 3</td>
                    <td>Column 4</td>
                    <td>Column 5</td>
                </tr>
                <tr>
                    <td>Column 1</td>
                    <td>Column 2</td>
                    <td>Column 3</td>
                    <td>Column 4</td>
                    <td>Column 5</td>
                </tr>
                <tr>
                    <td>Column 1</td>
                    <td>Column 2</td>
                    <td>Column 3</td>
                    <td>Column 4</td>
                    <td>Column 5</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

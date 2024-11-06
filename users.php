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
        <title>Users</title>
        <link rel="stylesheet" href="css/Users.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>

        </style>

    </head>
    <body>
    <?php 
        $pageTitle = "Resident Users";
        include 'header.php';
        include 'sidebar.php';
    ?>

   <div class="main-content">
    <div class="container mt-5">
    
        <!-- User Statistics Boxes -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-4">
                <div class="stat-box bg-primary text-center text-white py-3">
                    <h4>Registered Residents</h4>
                    <div class="stat-number">555</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box bg-success text-center text-white py-3">
                    <h4>Active Users</h4>
                    <div class="stat-number">245</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box bg-secondary text-center text-white py-3">
                    <h4>Inactive Users</h4>
                    <div class="stat-number">145</div>
                </div>
            </div>
        </div>

        <!-- User Table -->
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-light">
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
                    <tr>
                        <td>Kiyosaki</td>
                        <td>Roberto</td>
                        <td>123 Gumamela St., Post Proper Southside</td>
                        <td>45</td>
                        <td>Male</td>
                        <td>10/12/1975</td>
                        <td><button class="btn btn-danger">Delete</button></td>
                    </tr>
                    <tr>
                        <td>Reyes</td>
                        <td>Maria</td>
                        <td>456 Sampaguita St., Barangay Newtown</td>
                        <td>32</td>
                        <td>Female</td>
                        <td>07/24/1992</td>
                        <td><button class="btn btn-danger">Delete</button></td>
                    </tr>
                    <tr>
                        <td>Cruz</td>
                        <td>Juan</td>
                        <td>789 Daisy Ave., Barangay San Antonio</td>
                        <td>27</td>
                        <td>Male</td>
                        <td>03/15/1997</td>
                        <td><button class="btn btn-danger">Delete</button></td>
                    </tr>
                    <tr>
                        <td>Del Rosario</td>
                        <td>Angela</td>
                        <td>101 Sunflower St., Barangay Mabini</td>
                        <td>40</td>
                        <td>Female</td>
                        <td>09/18/1983</td>
                        <td><button class="btn btn-danger">Delete</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


    </body>
    </html>

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

// Safely retrieve and display user data
$firstname = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest';
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';

require_once '../connection/connection.php'; // Include your database connection file


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>  Admin Reservation </title>
    <link rel="stylesheet" href="./admindashboardcss/adminusers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    
    <!-- <a href="logout.php">Logout</a> -->

        <div class="row">
                <div class="left-content col-4">
                <div class="memoriallogo"><img src="../images/bogomemoriallogo.png" alt="bogomemoriallogo"></div>
                   <div class="hamburgermenu"><img src="../images/hamburgermenu.png" alt="hamburgermenu"></div> 
                   <div class="adminprofile">
                   <center><img src="../images/female.png" alt="adminicon">
                    <h2><?php echo $firstname; ?></h2></center>
                   </div>
                   <center>
                    <br>
                    <div class="adminlinks">
                            <span><img src="../images/dashboard.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminDashboard.php">Dashboard</a></span> 
                            <span><img src="../images/deceased.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminDeceased.php">Deceased</a></span>
                            <span><img src="../images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreservation.php">Reservation</a></span>
                            <span><img src="../images/review.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreviews.php">Reviews</a></span>
                            <span><img src="../images/settings.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminusers.php">User's</a></span>
                            <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminpayment.php">Payments</a></span>
                            <br>
                            <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
                        </div>
                    <br>
                </div>
                <div class="main">
                <div class="right-content1">
                <div class="right-header col-10">
                    <br>
                    <span><h1>Customer Data</h1></span>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <!-- <input type="text" class="search-input" placeholder="Search"> -->
                        <input type="text" id="search" class="search-input" placeholder="Search Customers ...">
                    </div>
                    <!-- <button onclick="openAddModal()">Add Reservation</button> -->
                </div>
                </div>
                <div class="right-content2">
                   <div class="right-header2 col-9">
                        <div class="left-content1">left</div>
                        <div class="right-content">right</div>
                   </div>
                </div>
                </div>
        </div>
</body>
</html>
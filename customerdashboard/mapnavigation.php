<?php

// Include the database connection
require_once '../connection/connection.php';




?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map Navigation</title>
    <link rel="stylesheet" href="./customerdashboard_css/customernavigation.css">
</head> 
<body>

    <!-- <a href="logout.php">Logout</a> -->

    <div class="row">
        <div class="left-content col-4">
            <div class="memoriallogo"><img src="../images/bogomemoriallogo.png" alt="bogomemoriallogo"></div>
            <div class="hamburgermenu"><img src="../images/hamburgermenu.png" alt="hamburgermenu"></div> 
            <div class="adminprofile">
                <center><img src="../images/female.png" alt="adminicon">
                <!-- <h2><?php echo $firstname; ?></h2></center> -->
            </div>
            <center>
                <br>
                <div class="adminlinks">
                    <span><img src="../images/dashboard.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/customerdashboard.php">Dashboard</a></span> 
                    <!-- <span><img src="../images/deceased.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/customerdeceased.php">Deceased</a></span> -->
                    <span><img src="../images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/customerreservation.php">Reservation</a></span>
                    <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/customerpayment.php">Payments</a></span>
                    <span><img src="../images/review.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/customerreviews.php">Reviews</a></span>
                    <span><img src="../images/map.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/mapnavigation.php">Map Navigation</a></span>
                    <!-- <span><img src="../images/settings.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/customersettings.php">Settings</a></span> -->
                    <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
                </div>
                <br>
            </center>
        </div>
        <div class="main">
            <div class="right-content1">
                <br>
                <br>
                <div class="right-header col-9">
                    <br>
                    <span><h1>Map Navigation</h1></span>
        
                </div>
            </div>
            <div class="right-content2">
               
            </div>
        </div>
    </div> 
</body>
</html>

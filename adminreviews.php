<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

// Safely retrieve and display user data
$firstname = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest';
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>  Deceased Person Information </title>
    <link rel="stylesheet" href="./CSS_FILE/adminreviews.css">
</head> 
<body>

    
    <!-- <a href="logout.php">Logout</a> -->

        <div class="row">
                <div class="left-content col-4">
                <div class="memoriallogo"><img src="./images/bogomemoriallogo.png" alt="bogomemoriallogo"></div>
                   <div class="hamburgermenu"><img src="./images/hamburgermenu.png" alt="hamburgermenu"></div> 
                   <div class="adminprofile">
                   <center><img src="./images/female.png" alt="adminicon">
                    <h2><?php echo $firstname; ?></h2></center>
                   </div>
                   <center>
                    <br>
                    <div class="adminlinks">
                    <span><img src="./images/dashboard.png" alt="">&nbsp;&nbsp;<a href="adminDashboard.php">Dashboard</a></span> 
                    <span><img src="./images/deceased.png" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="adminDeceased.php">Deceased</a></span>
                    <span><img src="./images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreservation.php">Reservation</a></span>
                    <span><img src="./images/review.png" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="adminreviews.php">Reviews</a></span>
                    <span><img src="./images/settings.png" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="adminsettings.php">Settings</a></span>
                    <span><img src="./images/payment.png" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="adminpayment.php">Payments</a></span>
                    <br>
                    <span><img src="./images/logout.png" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="logout.php">Logout</a></span>
                    </div>
                    <br>
                </div>
                <div class="main">
                <div class="right-content1">
                   <div class="right-header col-9">
                    <span><h1> Reviews
                    <!-- <br><?php if ($email): ?>
                    <p>You are logged in as <?php echo $email; ?>.</p>
                    <?php else: ?>
                    <p>Email address not available.</p>
                    <?php endif; ?></span> -->
                   </div>
                </div>
                <div class="right-content2">
                   <div class="right-header col-9">
                    <span><h1>Bogo Memorial Park <br>Admin Dashboard</h1>
                    <br><?php if ($email): ?>
                    <p>You are logged in as <?php echo $email; ?>.</p>
                    <?php else: ?>
                    <p>Email address not available.</p>
                    <?php endif; ?></span>
                   </div>
                </div>
                </div>
        </div>
</body>
</html>
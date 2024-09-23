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
    <title> Payment </title>
    <link rel="stylesheet" href="./admindashboardcss/adminpayment.css">
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
                            <!-- <span><img src="../images/settings.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminsettings.php">Settings</a></span> -->
                            <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminpayment.php">Payments</a></span>
                            <br>
                            <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
                        </div>
                    <br>
                </div>
                <div class="main">
                <div class="right-content1">
                   <div class="right-header col-9">
                    <br>
                    <br>
                    <span><h1 class="rightsidebar-content"> Payment </h1>
                   <!-- <a href=""><img src="../images/file.png" alt="" class="paymenthistory"></a> -->
                    <br>
                    <!-- <h3 class="rightsidebar-content2" > Choose Payment Method:</h3> -->
                    <div class="uppersidebar-content">
                        <div class="banktransfer">
                            <img src="../images/atm.png" alt="atmlogo">
                          <!-- <a href=""onclick="showContent> <p>BANK TRANSFER</p> </a> -->
                          <a href="#creditcard" onclick="showContent('content1')"><p>BANK TRANSFER</p> </a>
                        </div>
                        <div class="cash">
                            <img src="../images/cash.png" alt="cashlogo">
                            <!-- <a href=""><p>CASH</p></a> -->
                            <a href="#cash" onclick="showContent('content2')"><p>CASH</p>  </a>
                        </div>
                        <div class="other">
                            <img src="../images/file.png" alt="paymenthistorylogo">
                           <!-- <a href=""><p>Payment History</p></a>  -->
                           <a href="#cash" onclick="showContent('content3')"><p>PAYMENT HISTORY</p> </a>
                        </div>
                    </div>
                   </div>
                </div>
                <div class="right-content2">
                        <div class="right-header col-9">
                            <!-- <h1 class="creditcardinfo-header">Credit Card Information</h1> -->
                                <div id="content1" class="content">
                                    <form action="">
                                        <p>Card Information</p>
                                        <br>
                                        <label for="Card Holder Name">Card Holder Name</label>
                                        <br>
                                        <input type="text" class="input1" require>
                                        <br>
                                        <br>
                                        <label for="Cardnumber" >Cardnumber</label>
                                        <br>
                                        <input type="text" class="input1" require>
                                        <br>
                                        <br>
                                        <div class="Expiry">
                                        <div class="expiry1">
                                                <label for="Expiry Date">Expiry Date</label>
                                                <br>
                                            <input type="text" class="input2" require>
                                        </div>
                                            <div class="security">
                                                <label for="Security Code">Security Code</label>
                                                <br>
                                                <input type="text" class="input2"require>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <button type="submit" class="confirmbtn">Confirm Payment</button>
                                    </form>
                                </div>
                                <div id="content2" class="content">
                                <form action="">
                                        <p>Cash</p>
                                        <br>
                                        <label for="Card Holder Name">Name</label>
                                        <br>
                                        <input type="text" class="input1" require>
                                        <br>
                                        <br>
                                        <label for="Cardnumber" >Contact Number</label>
                                        <br>
                                        <input type="text" class="input1" require>
                                        <br>
                                        <br>
                                        <label for="Amount">Amount</label>
                                        <br>
                                        <input type="number" class="input1" require>
                                        <br>
                                        <br>
                                        <button type="submit" class="confirmbtn">Confirm Payment</button>
                                    </form>
                                </div>
                                <div id="content3" class="content">
                                    <form action="">Payment History</form>
                                </div>
                        </div>  
                    </div>
            
        </div>
        <script src="./admindashboardjs/adminpayment.js"></script>
</body>
</html>
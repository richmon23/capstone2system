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
    <link rel="stylesheet" href="/customerdashboard/customerdashboard_css/customerpayment.css">
</head> 
<body>

    
    <!-- <a href="logout.php">Logout</a> -->

        <div class="row">
        <div class="left-content col-3">
                        <div class="adminprofile">
                            <center>
                                <img src="../images/female.png" alt="adminicon">
                                <div class="dropdown">
                                    <button class="dropdown-btn">
                                        <?php echo "<h4> $firstname</h4>" ?> 
                                    </button>
                                    <!-- <i class="fas fa-caret-down dropdown-icon"></i> -->
                                    <!-- <div class="dropdown-content">
                                        <button onclick="openModal('changePasswordModal')">Change Password</button>
                                        <button onclick="openModal('termsModal')">Terms and Conditions</button>
                                    </div> -->
                                </div>
                            </center>
                        </div>
                        <br>
                        <div class="adminlinks">
                            <span><img src="../images/dashboard.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerDashboard.php">Dashboard</a></span> 
                            <!-- <span><img src="../images/deceased.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerDeceased.php">Deceased</a></span> -->
                            <span><img src="../images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerreservation.php">Reservation</a></span>
                            <span><img src="../images/review.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerreviews.php">Reviews</a></span>
                            <!-- <span><img src="../images/users.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerusers.php">User's</a></span> -->
                            <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerpayment.php">Payments</a></span>
                            <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
                        </div>
                        <br>
                    </div>
                <div class="main">
                <div class="right-content1">
                   <div class="right-header col-9">
                   <span>PAYMENT</h2>
                   </span>
                   <!-- <a href=""><img src="../images/file.png" alt="" class="paymenthistory"></a> -->
                    <br>
                    <br>
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
        <script src="./customerdashboardjs/customerpayment.js"></script>
</body>
</html>
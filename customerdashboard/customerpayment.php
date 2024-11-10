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

// Fetch user profile picture from the database
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT profile_pic FROM users WHERE id = :id");
$stmt->bindParam(':id', $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user profile picture exists
$profilePic = !empty($user['profile_pic']) ? $user['profile_pic'] : 'default.png'; // Use a default image if none is found

// Set installment details
$totalMonths = 6;
$amountDue = 100; // Change this to the actual monthly payable amount
$currentMonth = date("F"); // Get the current month

// Generate an array of the next 6 months from the current month
$months = [];
for ($i = 0; $i < $totalMonths; $i++) {
    $months[] = date("F", strtotime("+$i month"));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="/customerdashboard/customerdashboard_css/customerpayment.css">
</head> 
<body>

<div class="row">
    <div class="left-content col-3">
        <div class="adminprofile">
            <center>
                <img src="../uploads/profile_pics/<?php echo $profilePic; ?>" alt="Profile Picture">
                <div class="dropdown">
                    <button class="dropdown-btn">
                        <?php echo "<h4> $firstname</h4>" ?> 
                    </button>
                </div>
            </center>
        </div>
        <br>
        <div class="adminlinks">
            <span><img src="../images/dashboard.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerDashboard.php">Dashboard</a></span> 
            <span><img src="../images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerreservation.php">Reservation</a></span>
            <span><img src="../images/review.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerreviews.php">Reviews</a></span>
            <span><img src="../images/plot.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerviewavailableplot.php">Available Plot & Block</a></span>
            <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerpayment.php">Payments</a></span>
            <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
        </div>
        <br>
    </div>
    
    <div class="main">
        <div class="right-content1">
            <div class="right-header col-9">
                <span>PAYMENT</span>
                <br>
                <br>
                <br>
                <br>
                <h2 class="transactionheader">Your Transactions</h2>
                <div class="uppersidebar-content">
                    <br>
                    <div class="banktransfer">
                        <a href="#creditcard" onclick="showContent('content1')"><p>All</p></a>
                    </div>
                    <div class="cash">
                        <a href="#cash" onclick="showContent('content2')"><p>Cash</p></a>
                    </div>
                    <div class="cash">
                        <a href="#cash" onclick="showContent('content2')"><p>Wired</p></a>
                    </div>
                    <div class="other">
                        <a href="#installment" onclick="showContent('installment')"><p>Installment</p></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="right-content2">
            <div class="right-header col-9">
                <div id="content1" class="content">
                    <form action="">
                        <p>Card Information</p>
                        <label for="Card Holder Name">Card Holder Name</label><br>
                        <input type="text" class="input1" required><br><br>
                        <label for="Cardnumber">Cardnumber</label><br>
                        <input type="text" class="input1" required><br><br>
                        <div class="Expiry">
                            <div class="expiry1">
                                <label for="Expiry Date">Expiry Date</label><br>
                                <input type="text" class="input2" required>
                            </div>
                            <div class="security">
                                <label for="Security Code">Security Code</label><br>
                                <input type="text" class="input2" required>
                            </div>
                        </div>
                        <br><br>
                        <button type="submit" class="confirmbtn">Confirm Payment</button>
                    </form>
                </div>

                <div id="installment" class="installment">
                    <center><h3>Installment Payment - 6 Months</h3></center>
                    <br>
                    <div class="installment-details">
                        <div class="detail-header">
                            <div class="month">Month</div>
                            <div class="recommended-paydate">Recommended Date Payable</div>
                            <div class="amount-due">Amount Due</div>
                        </div>
                        <div class="detail-row">
                            <div class="month">
                            <button class="circle-btn"></button>
                                January  
                            </div>
                            <div class="recommended-paydate">01/15/2024</div>
                            <div class="amount-due">$100</div>
                        </div>
                        <div class="detail-row">
                            <div class="month">
                            <button class="circle-btn"></button>
                                February
                            </div>
                            <div class="recommended-paydate">02/15/2024</div>
                            <div class="amount-due">$100</div>
                        </div>
                        <div class="detail-row">
                            <div class="month">
                            <button class="circle-btn"></button>
                                March  
                            </div>
                            <div class="recommended-paydate">03/15/2024</div>
                            <div class="amount-due">$100</div>
                        </div>
                        <div class="detail-row">
                            <div class="month">
                            <button class="circle-btn"></button>
                                April
                            </div>
                            <div class="recommended-paydate">04/15/2024</div>
                            <div class="amount-due">$100</div>
                        </div>
                        <div class="detail-row">
                            <div class="month">
                            <button class="circle-btn"></button>
                                May
                            </div>
                            <div class="recommended-paydate">05/15/2024</div>
                            <div class="amount-due">$100</div>
                        </div>
                        <div class="detail-row">
                            <div class="month">
                            <button class="circle-btn"></button>
                                June
                            </div>
                            <div class="recommended-paydate">06/15/2024</div>
                            <div class="amount-due">$100</div>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</div>

<script src="./customerdashboardjs/customerpayment.js"></script>
</body>
</html>

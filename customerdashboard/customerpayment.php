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

// Fetch payment data for the user
$query = $conn->prepare("
    SELECT payment_id, total_amount, payment_method, payment_date, payment_status
    FROM payment 
    WHERE reservation_id IN (
        SELECT id FROM reservation WHERE user_id = :user_id
    )
");
$query->bindParam(':user_id', $userId, PDO::PARAM_INT);
$query->execute();
$payments = $query->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction</title>
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
        <!-- <span><img src="../images/deceased.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerDeceased.php">Deceased</a></span> -->
        <span><img src="../images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerreservation.php">Reservation</a></span>
        <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerpayment.php">Transaction</a></span>
        <span><img src="../images/plot.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerviewavailableplot.php">Available Plot & Block</a></span>
        <span><img src="../images/review.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerreviews.php">Reviews</a></span>
        <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
        </div>
        <br>
    </div>
    
    <div class="main">
        <div class="right-content1">
        <div class="right-header col-9">
    <span>PAYMENT</span>
    <br><br><br><br>
    <h2 class="transactionheader">Your Transactions</h2>
    
    <div class="uppersidebar-content">
        <br>
        <div class="all">
            <a href="#all" onclick="showContent('content1')"><p>All</p></a>
        </div>
        <div class="cash">
        <a href="#cash" onclick="showContent('content2', 'Cash')"><p>Cash</p></a>
        </div>
        <div class="wired">
            <a href="#gcash" onclick="showContent('content2', 'GCash')"><p>GCash</p></a>
        </div>

        <div class="installment">
            <a href="#installment" onclick="showContent('content4', 'Installment')"><p>Installment</p></a>
        </div>
    </div>
</div>

     
</div>


            <div class="right-content2">
                <div class="right-header col-9">

                    <!-- TODO:all -->
                    <div id="content1" class="content" style="display: none;">
                        <br>
                        <table>
                        <tr>
                            <th>Payment ID</th>
                            <th>Total Amount</th>
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th>Date</th>
                        </tr>
                        <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($payment['payment_id']); ?></td>
                            <td>$<?php echo htmlspecialchars($payment['total_amount']); ?></td>
                            <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                            <td><?php echo htmlspecialchars($payment['payment_status']); ?></td>
                            <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                        <br>
                    </div> 
                    <div id="content2" class="content" style="display: none;">
                        <!-- Cash payment data will be dynamically loaded here -->
                    </div>
                    <div class="wired">
                    <a href="#wired" onclick="showContent('content3', 'GCash')">
                    </div>
                    <div class="installment">
                            <!-- <a href="#installment" onclick="showContent('content4')"><p>Installment</p></a> -->
                    </div>
                </div>  
            </div>
        </div>
    </div>
<script>
    
    function showContent(contentId, paymentMethod = 'all') {
    // Hide all content sections
    document.querySelectorAll('.content').forEach(content => content.style.display = 'none');

    // Show the selected content
    const selectedContent = document.getElementById(contentId);
    if (selectedContent) {
        selectedContent.style.display = "block";

        // Fetch data only for specific payment methods
        if (paymentMethod === "Cash" || paymentMethod === "GCash") {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "fetch_cash_payments.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onload = function () {
                if (this.status === 200) {
                    selectedContent.innerHTML = this.responseText.trim() !== "" 
                        ? this.responseText 
                        : `<p style="color: red; text-align: center;">No ${paymentMethod} payments found.</p>`;
                } else {
                    console.error("Error fetching payments.");
                    selectedContent.innerHTML = "<p style='color: red; text-align: center;'>Error loading data.</p>";
                }
            };

            // Send payment method parameter
            xhr.send("payment_method=" + encodeURIComponent(paymentMethod));
        }
    }
}



    
    


</script>

<!-- <script src="./customerdashboardjs/customerpayment.js"></script> -->
</body>
</html>

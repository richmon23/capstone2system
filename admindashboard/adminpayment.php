<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

$firstname = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest';
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';

require_once '../connection/connection.php';

// Check if a search term is provided via GET
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare SQL query for approved reservations with a search filter on fullname
$sql = "SELECT firstname, package, plotnumber, blocknumber, status 
        FROM reservation 
        WHERE status = 'approved' 
        AND firstname LIKE :searchTerm";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if it's an AJAX request and return JSON response if true
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($result);
    exit();
}
 
 // Fetch user profile picture from the database
 $userId = $_SESSION['user_id'];
 $stmt = $conn->prepare("SELECT profile_pic FROM users WHERE id = :id");
 $stmt->bindParam(':id', $userId);
 $stmt->execute();
 $user = $stmt->fetch(PDO::FETCH_ASSOC);
 
 // Check if user profile picture exists
 $profilePic = !empty($user['profile_pic']) ? $user['profile_pic'] : 'default.png'; // Use a default image if none is found


//  TODO: fetch the payment data
 try {
    $stmt = $conn->prepare("SELECT payment_id, total_amount, payment_method, payment_status, payment_date FROM payment");
    $stmt->execute();

    // Fetch all results
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Transaction </title>
    <link rel="stylesheet" href="./admindashboardcss/adminpayment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
</head> 
<body>

    
    <!-- <a href="logout.php">Logout</a> -->

        <div class="row">
        <div class="left-content col-3"> 
        <div class="adminprofile">
                            <center>
                            <img src="../uploads/profile_pics/<?php echo $profilePic; ?>" alt="Profile Picture">
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
                            <span><img src="../images/dashboard.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminDashboard.php">Dashboard</a></span> 
                            <span><img src="../images/deceased.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminDeceased.php">Deceased</a></span>
                            <span><img src="../images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreservation.php">Reservation</a></span>
                            <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminpayment.php">Transaction</a></span>
                            <!-- <span><img src="../images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreservation.php">Transaction</a></span> -->
                            <span><img src="../images/users.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminusers.php">User's</a></span>  
                            <span><img src="../images/review.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreviews.php">Reviews</a></span>
                            
                            <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
                         </div>
                     <br>
                </div>

                <div class="main">
        <div class="right-content1">
            <div class="right-header col-9">
                <span> <h2>TRANSACTION</h2></span>
                <br>
                <br>
                <br>
                <br>
                <h2 class="transactionheader">Your Transactions</h2>
                <div class="uppersidebar-content">
                    <br>
                    <div class="all">
                        <a href="#all" onclick="showContent('content1')"><p>All</p></a>
                        </div>
                        <div class="cash">
                            <a href="#cash" onclick="showContent('content2')"><p>Cash</p></a>
                        </div>
                        <div class="wired">
                            <a href="#wired" onclick="showContent('content3')"><p>Wired</p></a>
                        </div>
                        <div class="installment">
                            <a href="#installment" onclick="showContent('content4')"><p>Installment</p></a>
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
                        <?php foreach ($result as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['payment_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    <br>
                </div>
                <div id="content2" class="content" style="display: none;">
                            <!-- display the content here -->
                </div>
                <div id="content3" class="content" style="display: none;">Wired Content</div>
                <div id="content4" class="content" style="display: none;">Installment Content</div>
                
            </div>  
        </div>
    </div>
</div>

<script>
            function showContent(contentId) {
    // Hide all content elements
    const allContents = document.querySelectorAll('.content');
    allContents.forEach(content => {
        content.style.display = 'none';
    });

    // Show the selected content and fetch data if necessary
    const selectedContent = document.getElementById(contentId);
    if (selectedContent) {
        if (selectedContent.style.display === "none" || selectedContent.style.display === "") {
            selectedContent.style.display = "block";

            // Check if fetching cash payments is required (specific to `content2`)
            if (contentId === "content2") {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "fetch_cash_payments.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                xhr.onload = function () {
                    if (this.status === 200) {
                        selectedContent.innerHTML = this.responseText;
                    } else {
                        console.error("Error fetching cash payments.");
                        selectedContent.innerHTML = "<p>Error loading data.</p>";
                    }
                };

                // Send request
                xhr.send("payment_method=cash");
            }
        }
    }
}



function showContent(contentId) {
    // Hide all content elements
    const allContents = document.querySelectorAll('.content');
    allContents.forEach(content => {
        content.style.display = 'none';
    });

    // Show the selected content and fetch data if necessary
    const selectedContent = document.getElementById(contentId);
    if (selectedContent) {
        if (selectedContent.style.display === "none" || selectedContent.style.display === "") {
            selectedContent.style.display = "block";

            // Fetch data for cash payments
            if (contentId === "content2") {
                fetchData("cash", selectedContent);
            }

            // Fetch data for GCash payments
            if (contentId === "content3") {
                fetchData("GCash", selectedContent);
            }


             // Fetch data for installment payments
             if (contentId === "content4") {
                fetchInstallmentData(selectedContent);
            }

        }
    }
}

function fetchData(paymentMethod, targetElement) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "fetch_payments.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (this.status === 200) {
            targetElement.innerHTML = this.responseText;
        } else {
            console.error(`Error fetching ${paymentMethod} payments.`);
            targetElement.innerHTML = "<p>Error loading data.</p>";
        }
    };

    // Send the request with the payment method
    xhr.send("payment_method=" + paymentMethod);
}

function fetchInstallmentData(targetElement) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "fetch_installments.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (this.status === 200) {
            targetElement.innerHTML = this.responseText;
        } else {
            console.error("Error fetching installment payments.");
            targetElement.innerHTML = "<p>Error loading data.</p>";
        }
    };

    // Send request to fetch installment data
    xhr.send("fetch_installments=true");
}




</script>

<!-- <script src="./customerdashboardjs/customerpayment.js"></script> -->



</body>
</html>
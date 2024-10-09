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

$sql = "SELECT  fullname,package, plotnumber, blocknumber, status FROM reservation WHERE status = 'approved'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Payment </title>
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
                                <img src="../images/female.png" alt="adminicon">
                                <div class="dropdown">
                                    <button class="dropdown-btn">
                                        <?php echo "<h4> $firstname</h4>" ?> 
                                    </button>
                                    <!-- <div class="dropdown-content">
                                        <button onclick="openModal('changePasswordModal')">Change Password</button>
                                    </div> -->
                                    <!-- <i class="fas fa-caret-down dropdown-icon"></i> -->
                                     <!-- <button onclick="openModal('termsModal')">Terms and Conditions</button> -->
                                </div>
                            </center>
                        </div>
                        <br>
                        <div class="adminlinks">
                            <span><img src="../images/dashboard.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminDashboard.php">Dashboard</a></span> 
                            <span><img src="../images/deceased.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminDeceased.php">Deceased</a></span>
                            <span><img src="../images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreservation.php">Reservation</a></span>
                            <span><img src="../images/review.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreviews.php">Reviews</a></span>
                            <span><img src="../images/users.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminusers.php">User's</a></span>
                            <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminpayment.php">Payments</a></span>
                            <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
                        </div>
                        <br>
                </div>
                <div class="main">
                <div class="right-content1">
                    <div class="right-header col-10">
                        <br>
                        <span><h2>PAYMENT</h2></span>
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <!-- <input type="text" class="search-input" placeholder="Search"> -->
                            <input type="text" id="search" class="search-input" placeholder="Search Customers ..." onkeyup="searchUsers()">
                        </div>
                        <!-- <button onclick="openAddModal()">Add Reservation</button> -->
                    </div>
                </div>
                <div class="right-content2">
                    <div class="right-header2 col-9">
                    <div class="left-content1">
                        <div class="table-container">
                        <table>
                            <tr>
                                <th>Name</th>
                                <th>Package</th>
                                <th>Plot Number</th>
                                <th>Block Number</th>
                                <th>Status</th>
                            </tr>
                            <?php
                            if ($result) {
                                foreach($result as $row) {
                                    echo "<tr>
                                            <td>" . htmlspecialchars($row["fullname"]) . "</td>
                                            <td>" . htmlspecialchars($row["package"]) . "</td>
                                            <td>" . htmlspecialchars($row["plotnumber"]) . "</td>
                                            <td>" . htmlspecialchars($row["blocknumber"]) . "</td>
                                            <td>" . htmlspecialchars($row["status"]) . "</td>
                                        </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No approved reservations found.</td></tr>";
                            }
                            ?>
                        </table>
                        </div>
                       
                        </div>
                        <div class="right-content">
                            2
                        </div>
                    </div>
                    
                </div>
            
        </div>
        
</body>
</html>
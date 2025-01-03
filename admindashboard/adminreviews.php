<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

// Safely retrieve and display user data
$firstname = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest';
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';

// Include the database connection
require_once '../connection/connection.php';

// Example PDO connection (make sure your connection.php is properly setting up the $pdo object)
$servername = "localhost"; // or your server name
$username = "root"; // your database username
$password = ""; // your database password
$dbname = "capstone2db"; // your database name

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


 // Fetch user profile picture from the database
 $userId = $_SESSION['user_id'];
 $stmt = $conn->prepare("SELECT profile_pic FROM users WHERE id = :id");
 $stmt->bindParam(':id', $userId);
 $stmt->execute();
 $user = $stmt->fetch(PDO::FETCH_ASSOC);
 
 // Check if user profile picture exists
 $profilePic = !empty($user['profile_pic']) ? $user['profile_pic'] : 'default.png'; // Use a default image if none is found
    


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews</title>
    <link rel="stylesheet" href="./admindashboardcss/adminreviews.css">
    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                                <span><img src="../images/users.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminusers.php">User's</a></span>
                                <span><img src="../images/review.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreviews.php">Reviews</a></span>
                                <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
                            </div>
                            <br>
                    </div>
                    <div class="main">
                <div class="right-content1">
                    <div class="right-header col-9">
                        <br>
                        <h2> CUSTOMER REVIEWS </h2>
                        <!-- <?php if ($email): ?>
                            <p>You are logged in as <?php echo $email; ?>.</p>
                        <?php else: ?>
                            <p>Email address not available.</p>
                        <?php endif; ?> -->
                    </div>
                </div>
                <div class="right-content2">
                    <div class="right-header col-9">
                    <div class="table-container">
    <?php
    $sql = "SELECT id, fullname, userfeedback, rating, time FROM reviews ORDER BY time DESC LIMIT 1000";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "
            <div class='card'>
                <div class='card-header'>
                    <h3>" . htmlspecialchars($row['fullname']) . "</h3>
                    <span class='time'>" . htmlspecialchars($row['time']) . "</span>
                </div>
                <div class='card-body'>
                    <p>" . htmlspecialchars($row['userfeedback']) . "</p>
                </div>
                <div class='card-footer'>
                    <div class='rating'>";
                    
            // Display stars based on the rating
            $rating = (int)$row['rating'];
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $rating) {
                    echo "<i class='fas fa-star'></i>"; // Filled star
                } else {
                    echo "<i class='far fa-star'></i>"; // Empty star
                }
            }

            echo "</div></div></div>";
        }
    } else {
        echo "<p>No reviews available.</p>";
    }
    ?>
</div>

                    </div>
                </div>
            </div>
        </div>
        <?php $pdo = null; // Closing the PDO connection ?>
</body>
</html>
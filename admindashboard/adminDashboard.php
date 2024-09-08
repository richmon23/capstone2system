<?php
session_start();



if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

$firstname = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest';
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';

// Include the database connection
require_once '../connection/connection.php'; // Include your database connection file

// Ensure $pdo is available before using it
if (isset($pdo)) {
    // Handling form submission for create, update, delete
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];

            if ($action == 'create') {
                // Check if all required fields are present
                if (!empty($_POST['fullname']) && !empty($_POST['package']) && !empty($_POST['plotnumber']) && !empty($_POST['blocknumber']) && !empty($_POST['email']) && !empty($_POST['contact']) && !empty($_POST['time'])) {
                    $stmt = $pdo->prepare("INSERT INTO reservation (fullname, package, plotnumber, blocknumber, email, contact, time) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$_POST['fullname'], $_POST['package'], $_POST['plotnumber'], $_POST['blocknumber'], $_POST['email'], $_POST['contact'], $_POST['time']]);
                } else {
                    echo "All fields are required.";
                }
            } elseif ($action == 'update') {
                if (!empty($_POST['id']) && !empty($_POST['fullname']) && !empty($_POST['package']) && !empty($_POST['plotnumber']) && !empty($_POST['blocknumber']) && !empty($_POST['email']) && !empty($_POST['contact']) && !empty($_POST['time'])) {
                    $stmt = $pdo->prepare("UPDATE reservation SET fullname = ?, package = ?, plotnumber = ?, blocknumber = ?, email = ?, contact = ?, time = ? WHERE id = ?");
                    $stmt->execute([$_POST['fullname'], $_POST['package'], $_POST['plotnumber'], $_POST['blocknumber'], $_POST['email'], $_POST['contact'], $_POST['time'], $_POST['id']]);
                } else {
                    echo "All fields are required.";
                }
            } elseif ($action == 'delete') {
                if (!empty($_POST['id'])) {
                    $stmt = $pdo->prepare("DELETE FROM reservation WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                } else {
                    echo "ID is required for deletion.";
                }
            }
        }
    }

    // Fetch data from the database
    $stmt = $pdo->prepare("SELECT * FROM reservation");
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo 'Database connection failed. Please try again later.';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>  Dashboard </title>
    <link rel="stylesheet" href="./admindashboardcss/admindashboard.css">
</head>
<body>
    <!-- <a href="logout.php">Logout</a> -->
        <div class="row">
                <div class="left-content col-3">
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
                            <span><img src="../images/settings.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminsettings.php">Settings</a></span>
                            <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminpayment.php">Payments</a></span>
                            <br>
                            <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
                        </div>
                        <br>
                </div>
                    <div class="main">
                        <div class="right-content1">
                            <div class="right-header col-11">
                                <span><h3>Bogo Memorial Park <br>Admin Dashboard</h3>
                                <br><?php if ($email): ?>
                                <!-- <p>You are logged in as <?php echo $email; ?>.</p>
                                <?php else: ?>
                                <p>Email address not available.</p>
                                <?php endif; ?></span> -->
                            </div>
                        </div>
                        <div class="right-content2">
                            <br>
                            <h2 class="todaydata">Today's  Data </h2>
                            <br>
                            <br>
                            <div class="rightsidebar-content">
                                <div class="div">Weekly Reserved</div>
                                <div class="div">Monthly Reseved</div>
                                <div class="div">Messages</div>
                                <div class="div">Available Plots</div>
                                <div class="div">Payment History</div>
                            </div>
                        </div>
                    </div>
              
        </div>
</body>
</html>
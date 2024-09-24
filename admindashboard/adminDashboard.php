<?php
session_start();



if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

// Initialize variables
$total_weekly_reservations = 0;
$total_monthly_reservations = 0;
$total_yearly_reservations = 0;



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

    if (isset($pdo)) {
    try {
        // Fetch number of reservations for the current week
        $weekly_sql = "
            SELECT COUNT(*) AS total_reservations
            FROM reservation
            WHERE YEARWEEK(time, 1) = YEARWEEK(CURDATE(), 1)";
        $weekly_stmt = $pdo->query($weekly_sql);
        $weekly_result = $weekly_stmt->fetch(PDO::FETCH_ASSOC);
        $total_weekly_reservations = $weekly_result['total_reservations'];

        // Fetch number of reservations for the current month
        $monthly_sql = "
            SELECT COUNT(*) AS total_reservations
            FROM reservation
            WHERE YEAR(time) = YEAR(CURDATE()) AND MONTH(time) = MONTH(CURDATE())";
        $monthly_stmt = $pdo->query($monthly_sql);
        $monthly_result = $monthly_stmt->fetch(PDO::FETCH_ASSOC);
        $total_monthly_reservations = $monthly_result['total_reservations'];
        
    } catch (PDOException $e) {
        // Display error if there is a connection problem
        echo "Error: " . $e->getMessage();
    }
} else {
    echo 'Database connection failed. Please try again later.';
    exit();
}

}

if (isset($pdo)) {
    try {
        // Fetch number of reservations for the current week
        $weekly_sql = "
            SELECT COUNT(*) AS total_reservations
            FROM reservation
            WHERE YEARWEEK(time, 1) = YEARWEEK(CURDATE(), 1)";
        $weekly_stmt = $pdo->query($weekly_sql);
        $weekly_result = $weekly_stmt->fetch(PDO::FETCH_ASSOC);
        $total_weekly_reservations = $weekly_result['total_reservations'];

        // Fetch number of reservations for the current month
        $monthly_sql = "
            SELECT COUNT(*) AS total_reservations
            FROM reservation
            WHERE YEAR(time) = YEAR(CURDATE()) AND MONTH(time) = MONTH(CURDATE())";
        $monthly_stmt = $pdo->query($monthly_sql);
        $monthly_result = $monthly_stmt->fetch(PDO::FETCH_ASSOC);
        $total_monthly_reservations = $monthly_result['total_reservations'];


        // Fetch number of reservations for the current year
        $yearly_sql = "
            SELECT COUNT(*) AS total_reservations
            FROM reservation
            WHERE YEAR(time) = YEAR(CURDATE())";
        $yearly_stmt = $pdo->query($yearly_sql);
        $yearly_result = $yearly_stmt->fetch(PDO::FETCH_ASSOC);
        $total_yearly_reservations = $yearly_result['total_reservations'];

        
    } catch (PDOException $e) {
        // Display error if there is a connection problem
        echo "Error: " . $e->getMessage();
    }
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
    <title>  Admin Dashboard </title>
    <link rel="stylesheet" href="./admindashboardcss/admindashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- <a href="logout.php">Logout</a> -->
        <div class="row">
                <div class="left-content col-3">
                    <div class="memoriallogo"><img src="../images/bogomemoriallogo.png" alt="bogomemoriallogo"></div>
                    <div class="hamburgermenu"><img src="../images/hamburgermenu.png" alt="hamburgermenu"></div> 
                    <div class="adminprofile">
                            <center>
                                <img src="../images/female.png" alt="adminicon">
                                <div class="dropdown">
                                    <button class="dropdown-btn">
                                        <?php echo "<h4> $firstname</h4>" ?> <i class="fas fa-caret-down dropdown-icon"></i>
                                    </button>
                                    <div class="dropdown-content">
                                        <button onclick="openModal('changePasswordModal')">Change Password</button>
                                        <!-- <button onclick="openModal('termsModal')">Terms and Conditions</button> -->
                                    </div>
                                </div>
                            </center>
                        </div>
                        <br>
                        <div class="adminlinks">
                            <span><img src="../images/dashboard.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminDashboard.php">Dashboard</a></span> 
                            <span><img src="../images/deceased.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminDeceased.php">Deceased</a></span>
                            <span><img src="../images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreservation.php">Reservation</a></span>
                            <span><img src="../images/review.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreviews.php">Reviews</a></span>
                            <!-- <span><img src="../images/settings.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminsettings.php">Settings</a></span> -->
                            <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminpayment.php">Payments</a></span>
                            <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
                        </div>
                        <br>
                </div>
                <div class="main">
                        <div class="right-content1">
                            <div class="right-header col-9">
                                <span>
                                    <h1>Bogo Memorial Park</h1>
                                    <h1>Admin Dashboard</h1>
                                </span>
                                <!-- <div class="search-box">
                                    <i class="fas fa-search search-icon"></i>
                                    <input type="text" class="search-input" placeholder="Search">
                                </div> -->
                            </div>
                        </div>
                        <div class="right-content2">
                            <br>
                            <h2 class="todaydata">Today's  Data </h2>
                            <br>
                            <br>
                            <div class="rightsidebar-content">
                            <div class="div">
                                <h3>Weekly Reservations</h3>
                                <p class="dashboard-counter-number"> <?php echo $total_weekly_reservations; ?></p>
                            </div>
                            <div class="div">
                                <h3>Monthly Reservations</h3>
                                <p class="dashboard-counter-number"> <?php echo $total_monthly_reservations; ?></p>
                            </div>
		                    <div class="div">
                                <h3>Yearly Reservations</h3>
                                <p class="dashboard-counter-number"> <?php echo $total_yearly_reservations; ?></p>
                            </div>
                            <!-- <div class="div"> -->
                                <!-- <h3>Messages</h3> -->
                                <!-- <p class="dashboard-counter-number"> <?php echo $total_reservations; ?></p> -->
                            <!-- </div> -->
                            <div class="div">
                                <h3>Available Plots</h3>
                            </div>
                            <!-- <div class="div">Payment History</div> -->
                        </div>
                        </div>
                    </div>
        </div>

        <!-- TODO: Alert message  -->
        <div id="alertMessage"></div>

         <!-- TODO: Change Password Modal  -->
        <div id="changePasswordModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Change Password</h1>
                    <button class="close-btn" onclick="closeModal('changePasswordModal')">&times;</button>
                </div>
                <br>
                <form action="change_password.php" method="POST">
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" id="currentPassword" name="currentPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" id="newPassword" name="newPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                    </div>
                    <button type="submit" class="btn-primary">Change Password</button>
                </form>
            </div>
        </div>

        <!-- Terms and Conditions Modal -->
        <!-- <div id="termsModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Terms and Conditions</h5>
                    <button class="close-btn" onclick="closeModal('termsModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <p> -->
                        <!-- Terms and conditions content goes here -->
                        <!-- Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla porttitor accumsan tincidunt.
                        Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Proin eget tortor risus.
                    </p>
                </div>
            </div>
        </div> -->

</body>
<script>
    // Function to open a modal
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
    }

    // Function to close a modal
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Close modal if clicked outside of modal content
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }



    // TODO: alert button functionality
      // Function to show an alert
      function showAlert(message, type) {
            const alertBox = document.getElementById('alertMessage');
            alertBox.innerText = message;
            alertBox.className = type; // Add type class to style differently
            alertBox.style.display = 'block';
            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 5000); // Hide the alert after 5 seconds
        }

        // Check URL parameters for messages
        const urlParams = new URLSearchParams(window.location.search);
        const successMessage = urlParams.get('success');
        const errorMessage = urlParams.get('error');

        if (successMessage) {
            showAlert(successMessage, 'success');
        } else if (errorMessage) {
            showAlert(errorMessage, 'error');
        }
</script>
</html>
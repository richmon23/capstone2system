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
    <title>  Admin Dashboard </title>
    <link rel="stylesheet" href="./admindashboardcss/admindashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @property --eb-progress-value {
  syntax: "<integer>";
  inherits: false;
  initial-value: 0;
}

@keyframes eb-progress {
  to {
    --eb-progress-value: var(--value);
  }
}



.eb-progress-bar {
  --size: 4.5rem;
  --inner-bg: #f2f2f2;
  --primary-color: var(--col);
  --secondary-color: #dfe0e0;
  display: flex;
  justify-content: center;
  align-items: center;
  box-shadow: 0 0px 2px rgb(0 0 0 / 40%), inset 0 0px 1px rgb(0 0 0 / 30%),
    0 1px 2px rgb(0 0 0 / 60%), 0 4px 2px rgb(0 0 0 / 20%),
    0 9px 4px rgb(0 0 0 / 10%), inset 0 2px 1px #ffffff8c;
  width: var(--size);
  height: var(--size);
  font-size: calc(var(--size) / 5);
  color: var(--primary-color);
  background: radial-gradient(
      closest-side,
      var(--inner-bg) 79%,
      transparent 80% 100%
    ),
    conic-gradient(
      var(--primary-color) calc(var(--eb-progress-value) * 1%),
      var(--secondary-color) 0
    );
  border-radius: 50%;

  &-wrapper {
    display: grid;
    place-items: center;
    gap: 0.5rem;
    padding: 1rem;
    transform: scale(1.5);
  }

  &,
  &::before {
    animation: eb-progress 2s 1 forwards;
  }

  &::before {
    counter-reset: percentage var(--eb-progress-value);
    content: counter(percentage) "%";
    font-weight: 400;
    font-family: Roboto;
    font-size: 15px;
    color: #9e9e9e;

    background: #f5f6f7;
    background-image: -webkit-gradient(
      linear,
      right top,
      right bottom,
      color-stop(0%, #ffffff),
      color-stop(100%, #d3d1cb)
    );

    box-shadow: 1px 1px 3px rgb(0 0 0 / 15%);
    padding: 20px 15px;
    border-radius: 50%;
    z-index: 1;
    position: relative;
    border: 1px solid #d0d0d0;
  }

  &-title {
    text-align: center;
    color: inherit;
    letter-spacing: 2px;
    line-height: 1.2;
    text-transform: uppercase;
    font-size: 11px;
    font-weight: 400;
    font-family: Roboto;
  }

  progress {
    visibility: hidden;
    width: 0;
    height: 0;
  }
}

h2 {
  font-family: "integralcfmedium" !important;
  margin-top: 6px;
}

@media screen and (min-width: 768px) {
  h1 {
    font-size: 64px;
  }
}

    </style>
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
                            <div class="header">
                            <span><h2>ADMIN DASHBOARD</h2></span>
                            </div>
                            <span class="button-container">
                                <a href="adminviewavailableplot.php">
                                    <button id="image-button1" title="View Available Plots">View  Plots</button>
                                </a>
                                <a href="adminmapnavigation.php">
                                    <button id="image-button2" title="Map Navigation">Map Navigation</button>
                                </a>
                            </span>
                        </div>

                            </div>
                        <div class="right-content2">
                            <br>
                            <h3 class="todaydata">Today's  Data </h3>
                            <br>
                            <br>
                            <br>
                            <div class="rightsidebar-content">
                                        <div class="div">
                                        
                                            <div class="eb-progress-bar-wrapper">
                                                <div class="eb-progress-bar html" style="--value: 1; --col: #FF5089">
                                                    <progress id="html" min="0" max="100" value="85"></progress>
                                                </div>
                                                <p class="dashboard-counter-number"> <?php echo $total_weekly_reservations; ?></p>
                                                <h2>Weekly Reservations</h2>
                                                <!-- <label for="html" class="eb-progress-bar-title">
                                                    <h2>Html</h2>
                                                </label> -->
                                            </div>
                                        </div>
                                        <div class="div">
                                            
                                            <div class="eb-progress-bar-wrapper">
                                                <div class="eb-progress-bar css" style="--value: 1; --col: #FF1659">
                                                    <progress id="css" min="0" max="100" value="91"></progress>
                                                </div>
                                                <p class="dashboard-counter-number"> <?php echo $total_monthly_reservations; ?></p>
                                                <h2>Monthly Reservations</h2>
                                                <!-- <label for="css" class="eb-progress-bar-title">
                                                    <h2>Css</h2>
                                                </label> -->
                                            </div>
                                        </div>
                                        <div class="div">
                                            
                                            <div class="eb-progress-bar-wrapper">
                                                <div class="eb-progress-bar js" style="--value: 1; --col: #E8232E">
                                                    <progress id="js" min="0" max="100" value="68"></progress>
                                                </div>
                                                <p class="dashboard-counter-number"> <?php echo $total_yearly_reservations; ?></p>
                                                <h2>Yearly Reservations</h2>
                                                <!-- <label for="js" class="eb-progress-bar-title">
                                                    <h2>JavaScript</h2>
                                                </label> -->
                                            </div>
                                        </div>
                                        <div class="div">
                                <div class="eb-progress-bar-wrapper">
                                    <?php
                                    try {
                                        // Define total plots in your system
                                        $total_plots = 100;

                                        // Query to get the total available plots
                                        $sql = "SELECT COUNT(*) as total_available_plots FROM plots WHERE is_available = 1";
                                        $stmt = $pdo->prepare($sql); // Assuming $pdo is defined in your connection file
                                        $stmt->execute();

                                        // Fetch the result
                                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $total_available_plots = $result['total_available_plots'] ?? 0;

                                        // Calculate the dynamic progress value
                                        $progress_value = ($total_available_plots / $total_plots) * 100;
                                    } catch (PDOException $e) {
                                        // Handle potential errors
                                        echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
                                        $progress_value = 0; // Default to 0 in case of an error
                                    }
                                    ?>

                                    <!-- Dynamic Progress Bar -->
                                    <div class="eb-progress-bar js" style="--value: <?php echo $progress_value; ?>; --col: #E8232E">
                                        <progress id="js" min="0" max="100" value="<?php echo $progress_value; ?>"></progress>
                                    </div>
                                    <p  class="dashboard-counter-number"> <?php echo $total_available_plots; ?> / <?php echo $total_plots; ?></p> 
                                    <!-- Display Total Available Plots -->
                                    <h2>Available Plots</h2>
                                    <p style="color: dodgerblue; font-size: 20px; font-weight: bold;">
                                      
                                    </p>
                                </div>
                            </div>


                            <!-- <h2>Pending Payments</h2>    -->
                            <!-- <div class="div"> -->
                                <!-- <h3>Messages</h3> -->
                                <!-- <p class="dashboard-counter-number"> <?php echo $total_reservations; ?></p> -->
                            <!-- </div> -->
                            
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
        <div id="termsModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Terms and Conditions</h5>
                    <button class="close-btn" onclick="closeModal('termsModal')">&times;</button>
                </div>
                <div class="modal-body">
                    <p> 
                         Terms and conditions content goes here 
                         Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla porttitor accumsan tincidunt.
                        Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Proin eget tortor risus.
                    </p>
                </div>
            </div>
        </div> 

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

    
        // Function to open a modal
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.style.display = 'block';  // First, set display to block
            setTimeout(() => {
                modal.classList.add('show');  // Then, add the 'show' class after a slight delay to trigger animation
            }, 10);  // The delay ensures that the display change is rendered before the animation starts
        }

        // Function to close a modal
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('show');  // Remove the show class to trigger the fade-out animation
            setTimeout(() => {
                modal.style.display = 'none';  // Hide the modal after the animation ends
            }, 300);  // Delay matches the CSS transition duration (0.3s)
        }

        // Close modal if clicked outside of modal content
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                const modal = event.target;
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300); // Delay matches the transition time
            }
        };





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
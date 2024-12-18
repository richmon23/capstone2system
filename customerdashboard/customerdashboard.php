<?php
// Start the session
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

$firstname = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest';
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';

// Include the database connection
require_once '../connection/connection.php'; // Include your database connection file

// Fetch user profile picture from the database  
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT profile_pic FROM users WHERE id = :id");
$stmt->bindParam(':id', $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user profile picture exists
$profilePic = !empty($user['profile_pic']) ? $user['profile_pic'] : 'default.png'; // Use a default image if none is found



// TODO: fetch the reservation status from the database
// User ID (replace this with the actual session user ID)
$user_id = $_SESSION['user_id'];

try {
    // Query to check the reservation status
    $sql = "SELECT status FROM reservation WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql); // Assuming $pdo is defined in your connection file
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch result
    if ($stmt->rowCount() > 0) {
        // User has a reservation
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $reservation_status = $row['status'];
    } else {
        // User does not have a reservation
        $reservation_status = "Don't have reservation";
    }
} catch (PDOException $e) {
    // Handle potential errors
    die("Database error: " . $e->getMessage());
}


// TODO: display the available Plot
try {
    // Query to get the total available plots per block
    $sql = "SELECT block, COUNT(*) as available_plots FROM plots WHERE is_available = 1 GROUP BY block";
    $stmt = $pdo->prepare($sql); // Assuming $pdo is defined in your connection file
    $stmt->execute();

    // Fetch the results
    $available_plots_by_block = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if the query returns any results
    if (empty($available_plots_by_block)) {
        echo "No available plots found in the database.";
    }

} catch (PDOException $e) {
    // Handle potential errors
    die("Database error: " . $e->getMessage());
}

// Initialize counts for each block
$available_block1 = 0;
$available_block2 = 0;
$available_block3 = 0;
$available_block4 = 0;

// Process the results
foreach ($available_plots_by_block as $plot) {
    // Debugging to check the block and available plots values
    // echo "Block: " . $plot['block'] . " - Available Plots: " . $plot['available_plots'] . "<br>";

    if ($plot['block'] == 'BLOCK1') {
        $available_block1 = $plot['available_plots'];
    } elseif ($plot['block'] == 'BLOCK2') {
        $available_block2 = $plot['available_plots'];
    } elseif ($plot['block'] == 'BLOCK3') {
        $available_block3 = $plot['available_plots'];
    } elseif ($plot['block'] == 'BLOCK4') {
        $available_block4 = $plot['available_plots'];
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard </title>
    <link rel="stylesheet" href="./customerdashboard_css/customerdashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                            <span>CUSTOMER DASHBOARD</span>
                            <!-- <span class="button-container">
                                <a href="customermapnavigation.php">
                                    <button id="image-button" title="Map Navigation">Map Navigation</button>
                                </a>
                            </span> -->
                        </div>
                </div>
            <div class="right-content2">
                <br>
                <div class="rightsidebar-content">
                <div class="div" style="font-size:20px;padding-top:20px;">Reservation Status: 
                <br>
                <br>
                    <!-- TODO: status of the reservation (e.g., pending, confirmed, cancelled) -->
                    <span style="color:dodgerblue;font-weight:bold"><?php echo htmlspecialchars($reservation_status); ?>
                    
                </div>
                </span>

                <!-- TODO: display the available plots in the park -->
                <div class="div" style="font-size:20px;padding-top:20px;">Available <br> Plots: 
                <br>
                <br>
                <?php
                    try {
                        // Query to get the total available plots
                        $sql = "SELECT COUNT(*) as total_available_plots FROM plots WHERE is_available = 1";
                        $stmt = $pdo->prepare($sql); // Assuming $pdo is defined in your connection file
                        $stmt->execute();

                        // Fetch the result
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        $total_available_plots = $result['total_available_plots'] ?? 0;

                            // Display the total available plots
                        echo "<p style='color: dodgerblue;'><strong>$total_available_plots</strong></p>";
                        } catch (PDOException $e) {
                            // Handle potential errors
                            echo "Database error: " . $e->getMessage();
                        }
                        ?>
                    </div>
                    </span>
                    
                 </div>
                 <div class="navigation-content">
                    <center><h2> Map Navigation</h2></center>
                    <br>
                    <p>To successfully navigate this map, take note of the clearly marked routes and key landmarks in the area, ensuring you stay oriented by checking your position frequently and adjusting your path as needed.</p>
                    <br>
                    <img src="/images/bogomap.png" alt="bogomap">
                    <div class="location">
                         Click here to Navigate 
                     <a href="customermapnavigation.php">
                        <img src="/images/location.png" alt="location">
                    </a>
                    </div>
                 </div>
                 <br>
                 <div class="right_content_div1">
                 <br>
                 <br>
                 <h1> WHAT'S NEW</h1>   
                 <br>              
                 <p>Our memorial park features plot packages that honor loved ones in serene settings. We also offer promotional deals for families, helping create lasting tributes in a peaceful environment.</p>
                 </div>
                    <div class="right-content3">
                        <br>
                        <br>
                        <div class="right_content3-div">
                        <div class="div1"><img src="../images/cemetery.jpg" alt="customerdashboardpic"></div>
                        <div class="div2"><img src="../images/family.jpg" alt="customerdashboardpic"></div>
                        <div class="div3"><img src="../images/memorialparkpic.jpg" alt="customerdashboardpic"></div>
                        </div>
                        <div class="right_content-div2">
                            <div><p>The Standard Package includes a landscaped plot with maintenance. The Deluxe Package features enhanced landscaping and a personalized marker. The Ultimate Package offers a premium plot with custom features and regular upkeep.</p></div>
                            <br>
                            <div><p>The Family Package provides multiple adjoining plots for loved ones with coordinated landscaping. The Memorial Tribute Package includes a plot, a personalized marker, and a custom tribute bench for remembrance.</p></div>
                            <br>
                            <div class="content-3"><p>The Family Package offers adjoining plots for loved ones with unified landscaping. The Tribute Package includes a plot,personalized marker, and a commemorative bench for remembrance.</p></div>
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
                        <!-- Terms and conditions content goes here -->
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla porttitor accumsan tincidunt.
                        Curabitur arcu erat, accumsan id imperdiet et, porttitor at sem. Proin eget tortor risus.
                    </p>
                </div>
            </div>
        </div>
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
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
            </div>
        </div>
    </div> 
</body>
</html>


                
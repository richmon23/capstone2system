<?php // Start the session

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

$firstname = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest';
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';

// Include the database connection
require_once '../connection/connection.php'; // Include your database connection file


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
                                <img src="../images/female.png" alt="adminicon">
                                <div class="dropdown">
                                    <button class="dropdown-btn">
                                        <?php echo "<h4> $firstname</h4>" ?> <i class="fas fa-caret-down dropdown-icon"></i>
                                    </button>
                                    <div class="dropdown-content">
                                        <button onclick="openModal('changePasswordModal')">Change Password</button>
                                        <button onclick="openModal('termsModal')">Terms and Conditions</button>
                                    </div>
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
                                    <span>CUSTOMER DASHBOARD</h2>
                                    </span>
                                    <!-- <div class="search-box">
                                        <i class="fas fa-search search-icon"></i>
                                        <input type="text" class="search-input" placeholder="Search">
                                    </div> -->
                                </div>
                            </div>
            <div class="right-content2">
                <br>
                <div class="rightsidebar-content">
                    <div class="div">Reservation Status</div>
                    <div class="div">Available Plots</div>
                   
                 </div>
                 <div class="navigation-content">nav</div>
                 <br>
                 <h4> WHAT'S NEW</h4>
                    <div class="right-content3">
                            <p>Our memorial park features plot packages that honor loved ones in serene settings. We also offer promotional deals for families, helping create lasting tributes in a peaceful environment.</p>
                        <br>
                        <br>
                        <div class="right_content3-div">
                        <div class="div1"><img src="../images/memorialparkpic.jpg" alt=""></div>
                        <div class="div2"><img src="../images/memorialparkpic.jpg" alt=""></div>
                        <div class="div3"><img src="../images/memorialparkpic.jpg" alt=""></div>
                        </div>
                        <div class="right_content-div2">
                            <br>
                            <div>The Standard Package includes a landscaped plot with maintenance. The Deluxe Package features enhanced landscaping and a personalized marker. The Ultimate Package offers a premium plot with custom features and regular upkeep.</div>
                            <br>
                            <div>The Family Package provides multiple adjoining plots for loved ones with coordinated landscaping. The Memorial Tribute Package includes a plot, a personalized marker, and a custom tribute bench for remembrance.</div>
                            <br>
                            <div>The Family Package offers adjoining plots for loved ones with unified landscaping. The Tribute Package includes a plot, personalized marker, and a commemorative bench for remembrance.</div>
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


                
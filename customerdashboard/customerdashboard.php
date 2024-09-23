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
                             <button onclick="openModal('termsModal')">Terms and Conditions</button>
                        </div>
                    </div>
                </center>
            </div>
             <br>
             <div class="adminlinks">
                <span><img src="../images/dashboard.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/customerdashboard.php">Dashboard</a></span> 
                <!-- <span><img src="../images/deceased.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/customerdeceased.php">Deceased</a></span> -->
                <span><img src="../images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/customerreservation.php">Reservation</a></span>
                <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/customerpayment.php">Payments</a></span>
                <span><img src="../images/review.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/customerreviews.php">Reviews</a></span>
                <span><img src="../images/map.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/mapnavigation.php">Map Navigation</a></span>
                <!-- <span><img src="../images/settings.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/customersettings.php">Settings</a></span> -->
                <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
             </div>
            <br>
        </div>
        <div class="main">
            <div class="right-content1">
                <div class="right-header col-9">
                        <span>
                            <h1>Bogo Memorial Park</h1>
                            <h1>Customer Dashboard</h1>
                        </span>
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="Search">
                        </div>
                </div>
            </div>   
            <div class="right-content2">
                <br>
                <h2 class="todaydata">Today's  Data </h2>
                <br>
                <br>
                <div class="rightsidebar-content">
                    <div class="div">Reservation Status</div>
                    <!-- <div class="div">Monthly Reseved</div> -->
                    <!-- <div class="div">Messages</div> -->
                    <div class="div">Available Plots</div>
                    <!-- <div class="div">Payment History</div> -->
                 </div>
            </div>
                <!-- Modal for Adding a New Reservation -->
                <!-- <div id="addModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeAddModal()">&times;</span>
                        <div class="modal-header">
                            <h2>Add New Data </h2>
                        </div>
                        <div class="modal-body">
                            <form id="addForm" method="post">
                                <input type="hidden" name="action" value="create">
                                <label for="fullname">Full Name:</label><br>
                                <input type="text" id="add_fullname" name="fullname" required class="form-element"><br><br>
                                <label for="address">Address:</label><br>
                                <input type="text" id="add_address" name="address" required class="form-element"><br><br>
                                <label for="born">Born:</label><br>
                                <input type="date" id="add_born" name="born" required class="form-element"><br><br>
                                <label for="died">Died:</label><br>
                                <input type="date" id="add_died" name="died" required class="form-element"><br><br>
                                <label for="plot">Plot #:</label><br>
                                <input type="text" id="add_plot" name="plot" required class="form-element"><br><br>
                                <label for="block">Block #:</label><br>
                                <input type="text" id="add_block" name="block" required class="form-element"><br><br>
                                <label for="funeralday">Funeral Day:</label><br>
                                <input type="date" id="add_funeralday" name="funeralday" required class="form-element"><br><br>
                                <label for="datecreated">Date Created:</label><br>
                                <input type="date" id="add_datecreated" name="datecreated" required class="form-element"><br><br>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button class="button save button-save-modal" onclick="document.getElementById('addForm').submit()">Save</button>
                        </div>
                    </div>
                </div> -->

                <!-- Modal for Update -->
                <!-- <div id="updateModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <div class="modal-header">
                            <h2>Update Details</h2>
                        </div>
                        <div class="modal-body">
                            <form id="updateForm" method="post">
                                <input type="hidden" name="id" id="modal_id">
                                <input type="hidden" name="action" value="update" class="form-element">
                                <label for="fullname">Full Name:</label><br>
                                <input type="text" id="modal_fullname" name="fullname" class="form-element" ><br><br>
                                <label for="address">Address:</label><br>
                                <input type="text" id="modal_address" name="address" class="form-element"><br><br>
                                <label for="born">Born:</label><br>
                                <input type="date" id="modal_born" name="born" class="form-element"><br><br>
                                <label for="died">Died:</label><br>
                                <input type="date" id="modal_died" name="died"class="form-element"><br><br>
                                <label for="plot">Plot #:</label><br>
                                <input type="text" id="modal_plot" name="plot"class="form-element"><br><br>
                                <label for="block">Block #:</label><br>
                                <input type="text" id="modal_block" name="block"class="form-element"><br><br>
                                <label for="funeralday">Funeral Day:</label><br>
                                <input type="date" id="modal_funeralday" name="funeralday"class="form-element"><br><br>
                                <label for="datecreated">Date Created:</label><br>
                                <input type="date" id="modal_datecreated" name="datecreated"class="form-element"><br><br>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button class="button update" onclick="document.getElementById('updateForm').submit()">Save</button>
                        </div>
                    </div>
                </div> -->

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

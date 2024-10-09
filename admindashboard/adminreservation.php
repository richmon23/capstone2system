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
        // Fetch reserved plots
        $reservedPlotsStmt = $pdo->prepare("SELECT plotnumber, blocknumber FROM reservation");
        $reservedPlotsStmt->execute();
        $reservedPlots = $reservedPlotsStmt->fetchAll(PDO::FETCH_ASSOC);
        $reserved = [];
        
        foreach ($reservedPlots as $reservation) {
            $reserved[$reservation['blocknumber']][] = $reservation['plotnumber'];
        }
    // Handling form submission for create, update, delete
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];

            if ($action == 'create') {
                // Check if all required fields are present
                if (!empty($_POST['fullname']) && !empty($_POST['package']) && !empty($_POST['plotnumber']) && !empty($_POST['blocknumber']) && !empty($_POST['email']) && !empty($_POST['contact']) && !empty($_POST['time'])) {
                    // Insert reservation
                    $stmt = $pdo->prepare("INSERT INTO reservation (fullname, package, plotnumber, blocknumber, email, contact, time) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$_POST['fullname'], $_POST['package'], $_POST['plotnumber'], $_POST['blocknumber'], $_POST['email'], $_POST['contact'], $_POST['time']]);

                    // Mark the plot as unavailable
                    $updatePlot = $pdo->prepare("UPDATE plots SET is_available = 0 WHERE plot_number = ? AND block = ?");
                    $updatePlot->execute([$_POST['plotnumber'], $_POST['blocknumber']]);
                } else {
                    echo "All fields are required.";
                }
            } elseif ($action == 'update') {
                if (!empty($_POST['id']) && !empty($_POST['fullname']) && !empty($_POST['package']) && !empty($_POST['plotnumber']) && !empty($_POST['blocknumber']) && !empty($_POST['email']) && !empty($_POST['contact']) && !empty($_POST['time'])) {
                    // First, check if the plot number is changing
                    $reservationStmt = $pdo->prepare("SELECT plotnumber, blocknumber FROM reservation WHERE id = ?");
                    $reservationStmt->execute([$_POST['id']]);
                    $oldReservation = $reservationStmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Update the reservation
                    $stmt = $pdo->prepare("UPDATE reservation SET fullname = ?, package = ?, plotnumber = ?, blocknumber = ?, email = ?, contact = ?, time = ? WHERE id = ?");
                    $stmt->execute([$_POST['fullname'], $_POST['package'], $_POST['plotnumber'], $_POST['blocknumber'], $_POST['email'], $_POST['contact'], $_POST['time'], $_POST['id']]);
                    
                    // If the plot number has changed, mark the old plot as available and the new one as unavailable
                    if ($oldReservation['plotnumber'] != $_POST['plotnumber']) {
                        // Mark old plot as available
                        $markAvailable = $pdo->prepare("UPDATE plots SET is_available = 1 WHERE plot_number = ? AND block = ?");
                        $markAvailable->execute([$oldReservation['plotnumber'], $oldReservation['blocknumber']]);

                        // Mark new plot as unavailable
                        $updatePlot = $pdo->prepare("UPDATE plots SET is_available = 0 WHERE plot_number = ? AND block = ?");
                        $updatePlot->execute([$_POST['plotnumber'], $_POST['blocknumber']]);
                    }
                } else {
                    echo "All fields are required.";
                }
            } elseif ($action == 'delete') {
                if (!empty($_POST['id'])) {
                    // Get the plot number and block number before deletion
                    $reservationStmt = $pdo->prepare("SELECT plotnumber, blocknumber FROM reservation WHERE id = ?");
                    $reservationStmt->execute([$_POST['id']]);
                    $reservation = $reservationStmt->fetch(PDO::FETCH_ASSOC);

                    // Delete the reservation
                    $stmt = $pdo->prepare("DELETE FROM reservation WHERE id = ?");
                    $stmt->execute([$_POST['id']]);

                    // Mark the plot as available again
                    if ($reservation) {
                        $updatePlot = $pdo->prepare("UPDATE plots SET is_available = 1 WHERE plot_number = ? AND block = ?");
                        $updatePlot->execute([$reservation['plotnumber'], $reservation['blocknumber']]);
                    }
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
    <title>Reservation</title>
    <link rel="stylesheet" href="./admindashboardcss/adminreserved.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="row">
    <div class="left-content col-3"> 
                 <div class="adminprofile">
                            <center>
                                <img src="../images/female.png" alt="adminicon">
                                <div class="dropdown">
                                    <button class="dropdown-btn">
                                        <?php echo "<h4> $firstname</h4>" ?>
                                    </button>
                                    <!-- <i class="fas fa-caret-down dropdown-icon"></i> -->
                                    <!-- <div class="dropdown-content">
                                        <button onclick="openModal('changePasswordModal')">Change Password</button>
                                    </div> -->
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
                    <span><h2>RESERVATION</h2></span>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <!-- <input type="text" class="search-input" placeholder="Search"> -->
                        <input type="text" id="search" class="search-input" placeholder="Search reservations...">
                    </div>
                    <!-- <button onclick="openAddModal()">Add Reservation</button> -->
                </div>
            </div>
            <div class="right-content2">
                    <div class="right-header col-9">
                        <br>
                        <button class="btnadd" onclick="openAddModal()">+ Add</button>
                            <div class="table-wrapper">
                                <table id="myTable">
                                    <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Package</th>
                                            <th>Plot</th>
                                            <th>Block</th>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th>Time</th>
                                            <th> Status </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="result">
                                        <tr><td colspan="8">No reservations found.</td></tr>
                                    </tbody>
                                </table>
                            </div>  
                        </div>
                    </div>

                                        <!-- Modal for Adding a New Reservation -->
                        <div id="addModal" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeAddModal()">&times;</span>
                                <div class="modal-header">
                                    <h2>Add New Reservation</h2>
                                </div>
                                <div class="modal-body">
                                    <form id="addForm" method="post" onsubmit="return validateForm('addForm')">
                                        <input type="hidden" name="action" value="create">

                                        <div class="form-row">
                                            <div class="form-column">
                                                <!-- Full Name -->
                                                <label for="fullname">Full Name:</label><br>
                                                <input type="text" id="add_fullname" name="fullname" required class="form-element"><br><br>
                                                
                                                <!-- Package -->
                                                <label for="package">Package:</label><br>
                                                <select id="add_package" name="package" class="form-element">
                                                    <option value="">Select Package</option>
                                                    <option value="garden">Garden</option>
                                                    <option value="family_state">Family State</option>
                                                    <option value="lawn">Lawn</option>
                                                </select><br><br>
                                                
                                                <!-- Block Selection -->
                                                <label for="block">Block #:</label><br>
                                                <select id="add_block" name="blocknumber" required class="form-element">
                                                    <option value="" disabled selected>Select Block</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                </select><br><br>
                                                
                                                <!-- Email -->
                                                <label for="email">Email:</label><br>
                                                <input type="email" id="add_email" name="email" required class="form-element"><br><br>
                                            </div>
                                            
                                            <div class="form-column">
                                                <!-- Plot Selection -->
                                                <label for="plot">Plot #:</label><br>
                                                <select id="add_plot" name="plotnumber" required class="form-element">
                                                    <option value="" disabled selected>Select Plot</option>
                                                </select><br><br>

                                                <!-- Contact -->
                                                <label for="contact">Contact:</label><br>
                                                <input type="text" id="add_contact" name="contact" required class="form-element"><br><br>
                                                
                                                <!-- Time -->
                                                <label for="time">Time:</label><br>
                                                <input type="datetime-local" id="add_time" name="time" required class="form-element"><br><br>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button class="button-save-modal" onclick="document.getElementById('addForm').submit()">Save</button>
                                </div>
                            </div>
                        </div>

                    <!-- Modal for Update -->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-header">
            <h2>Update Details</h2>
        </div>
        <div class="modal-body">
            <form id="updateForm" method="post">
                <input type="hidden" name="id" id="modal_id">
                <input type="hidden" name="action" value="update" class="form-element">
                
                <div class="form-row">
                    <div class="form-column">
                        <!-- Full Name -->
                        <label for="fullname">Full Name:</label><br>
                        <input type="text" id="modal_fullname" name="fullname" class="form-element"><br><br>
                        
                        <!-- Package -->
                        <label for="package">Package:</label><br>
                        <select id="modal_package" name="package" class="form-element">
                            <option value="">Select Package</option>
                            <option value="garden">Garden</option>
                            <option value="family_state">Family State</option>
                            <option value="lawn">Lawn</option>
                        </select>
                        <br><br>
                        
                        <!-- Block Selection -->
                        <label for="block">Block #:</label><br>
                        <select id="modal_block" name="blocknumber" class="form-element" required>
                            <option value="" disabled selected>Select Block</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select><br><br>
                    </div>
                    <div class="form-column">
                        <!-- Plot Selection -->
                        <label for="plot">Plot #:</label><br>
                        <select id="modal_plot" name="plotnumber" class="form-element" required>
                            <option value="" disabled selected>Select Plot</option>
                        </select><br><br>

                        <!-- Email -->
                        <label for="email">Email:</label><br>
                        <input type="email" id="modal_email" name="email" class="form-element"><br><br>
                        
                        <!-- Contact -->
                        <label for="contact">Contact:</label><br>
                        <input type="text" id="modal_contact" name="contact" class="form-element"><br><br>
                        
                        <!-- Time -->
                        <label for="time">Date:</label><br>
                        <input type="date" id="modal_time" name="time" class="form-element"><br><br>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="button update" onclick="document.getElementById('updateForm').submit()">Save</button>
        </div>
    </div>
</div>


                    <!-- TODO: Status modal for changing reservation status -->
                     <!-- Modal Structure -->
                    <div id="statusModal" class="modal" style="display:none;">
                        <div class="modal-content">
                            <h4>Change Reservation Status</h4>
                            <br>
                            <form id="statusForm" method="POST" action="change_status.php">
                                <input type="hidden" id="reservationId" name="id">
                                
                                <label for="status">Select Status:</label>
                                <select id="status" name="status">
                                    <option value="Approved">Approved</option>
                                    <option value="Disapproved">Disapproved</option>
                                </select>
                                
                                <div class="modal-footer">
                                    <button type="submit" class="button save">Save Changes</button>
                                    <button type="button" class="button cancel" onclick="closeStatusModal()">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <script>
                        var addModal = document.getElementById("addModal");
                        var updateModal = document.getElementById("updateModal");

                        function openAddModal() {
                            addModal.style.display = "block";
                        }

                        function closeAddModal() {
                            addModal.style.display = "none";
                        }

                        function openModal(data) {
                            document.getElementById("modal_id").value = data.id;
                            document.getElementById("modal_fullname").value = data.fullname;
                            document.getElementById("modal_package").value = data.package;
                            document.getElementById("modal_plot").value = data.plotnumber;
                            document.getElementById("modal_block").value = data.blocknumber;
                            document.getElementById("modal_email").value = data.email;
                            document.getElementById("modal_contact").value = data.contact;

                            // Ensure the time is correctly formatted
                            if (data.time) {
                                var formattedTime = data.time.replace(" ", "T");
                                document.getElementById("modal_time").value = formattedTime;
                            }

                            updateModal.style.display = "block";
                        }


                        function closeModal() {
                            updateModal.style.display = "none";
                        }

                        window.onclick = function(event) {
                            if (event.target == addModal) {
                                closeAddModal();
                            }
                            if (event.target == updateModal) {
                                closeModal();
                            }
                        }

                         // TODO: CONFIRM TO DELETE BUTTON
                        function confirmDelete() {
                            if (confirm("Are you sure you want to delete this reservation?")) {
                                return true;
                            } else {
                                return false;
                            }
                        }


                        // TODO: search function jquery
                            $(document).ready(function() {
                            // Fetch all reservations initially
                            fetchReservations('');

                            // Search functionality
                            $('#search').on('keyup', function() {
                                var query = $(this).val();
                                fetchReservations(query);
                            });

                            function fetchReservations(query) {
                                $.ajax({
                                    url: "search.php",
                                    method: "POST",
                                    data: {query: query},
                                    success: function(data) {
                                        $('#result').html(data);
                                    }
                                });
                            }
                        });
                        

                        document.addEventListener("DOMContentLoaded", function () {
                        // When block changes, fetch available plots for the add modal
                        document.getElementById("add_block").addEventListener("change", function () {
                            var block = this.value;
                            fetchAvailablePlots(block, "add_plot");
                        });

                        // When block changes, fetch available plots for the update modal
                        document.getElementById("modal_block").addEventListener("change", function () {
                            var block = this.value;
                            fetchAvailablePlots(block, "modal_plot");
                        });
                    });

                    // Function to fetch available plots based on selected block
                    function fetchAvailablePlots(block, plotDropdownId) {
                        if (block) {
                            var xhr = new XMLHttpRequest();
                            xhr.open("GET", "get_plots.php?block=" + block, true);
                            xhr.onload = function () {
                                if (this.status === 200) {
                                    var plots = JSON.parse(this.responseText);
                                    var plotDropdown = document.getElementById(plotDropdownId);
                                    plotDropdown.innerHTML = '<option value="" disabled selected>Select Plot</option>'; // Clear previous options

                                    // Populate the dropdown with available plots
                                    plots.forEach(function (plot) {
                                        var option = document.createElement("option");
                                        option.value = plot;
                                        option.textContent = "Plot " + plot;
                                        plotDropdown.appendChild(option);
                                    });
                                } else {
                                    console.error("Failed to load plots: " + this.status);
                                }
                            };
                            xhr.send();
                        }
                    }

                    // TODO: status modal functionality
                     // Function to open the modal and set the current reservation ID
                    function openStatusModal(reservation) {
                        document.getElementById('reservationId').value = reservation.id; // Set the reservation ID in the form
                        document.getElementById('status').value = reservation.status;   // Set the current status
                        document.getElementById('statusModal').style.display = 'block'; // Show the modal
                    }

                    // Function to close the modal
                    function closeStatusModal() {
                        document.getElementById('statusModal').style.display = 'none'; // Hide the modal
                    }
                    </script>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


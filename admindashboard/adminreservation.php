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

        
        // TODO:update reservations table with updated data

        // if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
        //     if (!empty($_POST['id']) && !empty($_POST['fullname']) && !empty($_POST['package']) && !empty($_POST['plotnumber']) && !empty($_POST['blocknumber']) && !empty($_POST['email']) && !empty($_POST['contact']) && !empty($_POST['time'])) {
        //         // Prepare the SQL statement
        //         $stmt = $pdo->prepare("UPDATE reservation SET fullname = ?, package = ?, plotnumber = ?, blocknumber = ?, email = ?, contact = ?, time = ? WHERE id = ?");
                
        //         // Execute the update query
        //         if ($stmt->execute([$_POST['fullname'], $_POST['package'], $_POST['plotnumber'], $_POST['blocknumber'], $_POST['email'], $_POST['contact'], $_POST['time'], $_POST['id']])) {
        //             echo "<script>
        //                     document.getElementById('alert-box').innerHTML = 'Record updated successfully!';
        //                     document.getElementById('alert-box').className = 'alert success';
        //                     setTimeout(function() {
        //                         document.getElementById('alert-box').style.display = 'none';
        //                     }, 5000); // Auto-dismiss after 5 seconds
        //                   </script>";
        //         } else {
        //             echo "<script>
        //                     document.getElementById('alert-box').innerHTML = 'Error updating record.';
        //                     document.getElementById('alert-box').className = 'alert error';
        //                   </script>";
        //         }
        //     } else {
        //         echo "<script>
        //                 document.getElementById('alert-box').innerHTML = 'All fields are required.';
        //                 document.getElementById('alert-box').className = 'alert error';
        //               </script>";
        //     }
        // }
        
        
        
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
    <title>Admin Reservation</title>
    <link rel="stylesheet" href="./admindashboardcss/adminreserved.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="row">
        <div class="left-content col-4">
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
                            <!-- <span><img src="../images/settings.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminsettings.php">Settings</a></span> -->
                            <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminpayment.php">Payments</a></span>
                            <br>
                            <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
                        </div>
                <br>
            </center>
        </div>
        <div class="main">
            <div class="right-content1">
                <div class="right-header col-10">
                    <br>
                    <span><h1>Reservation</h1></span>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <!-- <input type="text" class="search-input" placeholder="Search"> -->
                        <input type="text" id="search" class="search-input" placeholder="Search reservations...">
                    </div>
                    <!-- <button onclick="openAddModal()">Add Reservation</button> -->
                </div>
            </div>
            <div class="addreservationvtn">
                <div class="right-header col-9">
                    <br>
                    <!-- <button onclick="openAddModal()"><img src="./images/review.png" alt=""></button> -->
                </div>
            </div>
            <div class="right-content2">
                <div class="right-header col-9">
                    <br>
                    <button class="btnadd "onclick="openAddModal()"><img src="../images/add-user.png" alt=""></button>
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
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="result">
                            <tr><td colspan="8">No reservations found.</td></tr>
                        </tbody>
                    </table>
                    </div>
                    <!-- Modal for Adding a New Reservation -->
                    <div id="addModal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeAddModal()">&times;</span>
                            <div class="modal-header">
                                <h2>Add New Reservation</h2>
                            </div>
                            <div class="modal-body">
                                <form id="addForm" method="post">
                                    <input type="hidden" name="action" value="create" class="form-element">
                                    <label for="fullname">Full Name:</label><br>
                                    <input type="text" id="add_fullname" name="fullname" required class="form-element"><br><br>
                                    <label for="package">Package:</label><br>
                                    <!-- <input type="text" id="add_package" name="package" required><br><br>
                                    <label for="choices">Choose an option:</label> -->
                                    <select id="add_package"  name="package" class="form-element">
                                        <option value="">Select Package</option>
                                        <option value="garden">Garden</option>
                                        <option value="family_state">Family State</option>
                                        <option value="lawn">Lawn</option>
                                    </select>
                                    <br>
                                    <br>
                                    <label for="plot">Plot #:</label><br>
                                    <input type="text" id="add_plot" name="plotnumber" required class="form-element"><br><br>
                                    <label for="block">Block #:</label><br>
                                    <input type="text" id="add_block" name="blocknumber" required class="form-element"><br><br>
                                    <label for="email">Email:</label><br>
                                    <input type="email" id="add_email" name="email" required class="form-element"><br><br>
                                    <label for="contact">Contact:</label><br>
                                    <input type="text" id="add_contact" name="contact" required class="form-element"><br><br>
                                    <label for="time">Time:</label><br>
                                    <input type="datetime-local" id="add_time" name="time" required class="form-element"><br><br>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button class="button save button-save-modal" onclick="document.getElementById('addForm').submit()">Save</button>
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
                                    <label for="fullname">Full Name:</label><br>
                                    <input type="text" id="modal_fullname" name="fullname" class="form-element"><br><br>
                                    <label for="package">Package:</label><br>
                                    <!-- <input type="text" id="modal_package" name="package"><br><br> -->
                                    <select id="modal_package"  name="package" class="form-element">
                                        <option value="">Select Package</option>
                                        <option value="garden">Garden</option>
                                        <option value="family_state">Family State</option>
                                        <option value="lawn">Lawn</option>
                                    </select>
                                    <br>
                                    <br>
                                    <label for="plot">Plot #:</label><br>
                                    <input type="text" id="modal_plot" name="plotnumber" class="form-element"><br><br>
                                    <label for="block">Block #:</label><br>
                                    <input type="text" id="modal_block" name="blocknumber" class="form-element"><br><br>
                                    <label for="email">Email:</label><br>
                                    <input type="email" id="modal_email" name="email"class="form-element"><br><br>
                                    <label for="contact">Contact:</label><br>
                                    <input type="text" id="modal_contact" name="contact"class="form-element"><br><br>
                                    <label for="time">Date:</label><br>
                                    <input type="date" id="modal_time" name="time"class="form-element"><br><br>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button class="button update" onclick="document.getElementById('updateForm').submit()">Save</button>
                            </div>
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

                    </script>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

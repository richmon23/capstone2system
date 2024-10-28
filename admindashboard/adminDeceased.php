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
    $reservedPlotsStmt = $pdo->prepare("SELECT plot, block FROM deceasedpersoninfo");
    $reservedPlotsStmt->execute();
    $reservedPlots = $reservedPlotsStmt->fetchAll(PDO::FETCH_ASSOC);
    $reserved = [];
    
    foreach ($reservedPlots as $reservation) {
        $reserved[$reservation['block']][] = $reservation['plot'];
    }

    // Handling form submission for create, update, delete
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];

            if ($action == 'create') {
                // Check if all required fields are present
                if (!empty($_POST['firstname']) && !empty($_POST['surname']) && !empty($_POST['address']) && !empty($_POST['born']) && !empty($_POST['died']) && !empty($_POST['plot']) && !empty($_POST['block']) && !empty($_POST['funeralday'])) {
                    // Insert deceased person info
                    $stmt = $pdo->prepare("INSERT INTO deceasedpersoninfo (firstname,surname, address, born, died, plot, block, funeralday, datecreated) VALUES (?,?, ?, ?, ?, ?, ?, ?, NOW())");
                    $stmt->execute([$_POST['firstname'], $_POST['surname'], $_POST['address'], $_POST['born'], $_POST['died'], $_POST['plot'], $_POST['block'], $_POST['funeralday']]);

                    // Mark the plot as unavailable
                    $updatePlot = $pdo->prepare("UPDATE plots SET is_available = 0 WHERE plot_number = ? AND block = ?");
                    $updatePlot->execute([$_POST['plot'], $_POST['block']]);
                } else {
                    echo "All fields are required.";
                }
            } elseif ($action == 'update') {
                if (!empty($_POST['id']) && !empty($_POST['firstname'])&& !empty($_POST['surname']) && !empty($_POST['address']) && !empty($_POST['born']) && !empty($_POST['died']) && !empty($_POST['plot']) && !empty($_POST['block']) && !empty($_POST['funeralday'])) {
                    // First, check if the plot number is changing
                    $reservationStmt = $pdo->prepare("SELECT plot, block FROM deceasedpersoninfo WHERE id = ?");
                    $reservationStmt->execute([$_POST['id']]);
                    $oldReservation = $reservationStmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Update the deceased person info
                    $stmt = $pdo->prepare("UPDATE deceasedpersoninfo SET firstname = ?, surname = ?, address = ?, born = ?, died = ?, plot = ?, block = ?, funeralday = ? WHERE id = ?");
                    $stmt->execute([$_POST['firstname'],$_POST['surname'], $_POST['address'], $_POST['born'], $_POST['died'], $_POST['plot'], $_POST['block'], $_POST['funeralday'], $_POST['id']]);

                    // If the plot number has changed, mark the old plot as available and the new one as unavailable
                    if ($oldReservation['plot'] != $_POST['plot']) {
                        // Mark old plot as available
                        $markAvailable = $pdo->prepare("UPDATE plots SET is_available = 1 WHERE plot_number = ? AND block = ?");
                        $markAvailable->execute([$oldReservation['plot'], $oldReservation['block']]);

                        // Mark new plot as unavailable
                        $updatePlot = $pdo->prepare("UPDATE plots SET is_available = 0 WHERE plot_number = ? AND block = ?");
                        $updatePlot->execute([$_POST['plot'], $_POST['block']]);
                    }
                } else {
                    echo "All fields are required.";
                }
            } elseif ($action == 'delete') {
                if (!empty($_POST['id'])) {
                    // Get the plot number and block number before deletion
                    $reservationStmt = $pdo->prepare("SELECT plot, block FROM deceasedpersoninfo WHERE id = ?");
                    $reservationStmt->execute([$_POST['id']]);
                    $reservation = $reservationStmt->fetch(PDO::FETCH_ASSOC);

                    // Delete the deceased person info
                    $stmt = $pdo->prepare("DELETE FROM deceasedpersoninfo WHERE id = ?");
                    $stmt->execute([$_POST['id']]);

                    // Mark the plot as available again
                    if ($reservation) {
                        $updatePlot = $pdo->prepare("UPDATE plots SET is_available = 1 WHERE plot_number = ? AND block = ?");
                        $updatePlot->execute([$reservation['plot'], $reservation['block']]);
                    }
                } else {
                    echo "ID is required for deletion.";
                }
            }
        }
    }

    // Fetch data from the database
    $stmt = $pdo->prepare("SELECT * FROM deceasedpersoninfo");
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Deceased Person Information</title>
    <link rel="stylesheet" href="./admindashboardcss/admindeceasedinfo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head> 
<body>

    <!-- <a href="logout.php">Logout</a> -->

    <div class="row">
    <div class="left-content col-4">
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
                    <span><img src="../images/review.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreviews.php">Reviews</a></span>
                    <span><img src="../images/users.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminusers.php">User's</a></span>
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
                    <span><h2>DECEASED INFORMATION</h2></span>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <!-- <input type="text" class="search-input" placeholder="Search"> -->
                        <input type="text" id="search" class="search-input" placeholder="Search Deceased info...">
                    </div>
                    <!-- <button onclick="openAddModal()">Add Reservation</button> -->
                </div>
            </div>
            <div class="right-content2">
                <br>
                <button class="btnadd" onclick="openAddModal()">+ Add</button>

                <div class="table-wrapper">
                <table id="myTable">
                        <thead>
                            <tr>
                                <th>Firstame</th>
                                <th>Surname</th>
                                <th>Address</th>
                                <th>Born</th>
                                <th>Departed</th>
                                <th>Plot</th>
                                <th>Block</th>
                                <th>Funeral Day</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <!-- TODO: display the data in table -->
                        <tbody id="result">
                            <tr><td colspan="8">No Information found.</td></tr>
                        </tbody>
                    </table>
                </div>   
                <!-- Modal for Adding a New Deceased Person -->
                <div id="addModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeAddModal()">&times;</span>
                    <div class="modal-header">
                        <h2>Add New Data</h2>
                    </div>
                    <div class="modal-body">
                        <form id="addForm" method="post" onsubmit="return validateForm('addForm')">
                            <input type="hidden" name="action" value="create">
                            <div class="form-row">
                                <div class="form-column">
                                    <!-- Full Name -->
                                    <label for="firstname">Firstname:</label><br>
                                    <input type="text" id="add_firstname" name="firstname" required class="form-element"><br><br>

                                    <label for="surname">Surname:</label><br>
                                    <input type="text" id="add_surname" name="surname" required class="form-element"><br><br>
                                    
                                    
                                    <!-- Address -->
                                    <label for="address">Address:</label><br>
                                    <input type="text" id="add_address" name="address" required class="form-element"><br><br>
                                    
                                    <!-- Born -->
                                    <label for="born">Born:</label><br>
                                    <input type="date" id="add_born" name="born" required class="form-element"><br><br>
                                    
                                    <!-- Block Selection -->
                                    <label for="block">Block #:</label><br>
                                    <select id="add_block" name="block" required class="form-element">
                                        <option value="" disabled selected>Select Block</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select><br><br>
                                </div>
                                <div class="form-column">
                                    <!-- Died -->
                                    <label for="died">Died:</label><br>
                                    <input type="date" id="add_died" name="died" required class="form-element"><br><br>
                                    
                                    <!-- Funeral Day -->
                                    <label for="funeralday">Funeral Day:</label><br>
                                    <input type="date" id="add_funeralday" name="funeralday" required class="form-element"><br><br>

                                    <!-- Date Created -->
                                    <label for="datecreated">Date Created:</label><br>
                                    <input type="date" id="add_datecreated" name="datecreated" required class="form-element"><br><br>

                                    <!-- Plot Selection -->
                                    <label for="plot">Plot #:</label><br>
                                    <select id="add_plot" name="plot" required class="form-element">
                                        <option value="" disabled selected>Select Plot</option>
                                    </select><br><br>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="button-save-modal" onclick="document.getElementById('addForm').submit()">Save</button>
                    </div>
                </div>
            </div>

                <!-- Modal for Updating Deceased Person Details -->
                <div id="updateModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <div class="modal-header">
                            <h2>Update Details</h2>
                        </div>
                        <div class="modal-body">
                            <form id="updateForm" method="post" onsubmit="return validateForm('updateForm')">
                                <input type="hidden" name="id" id="modal_id">
                                <input type="hidden" name="current_plot" id="current_plot">
                                <input type="hidden" name="action" value="update">
                                
                                <div class="form-row">
                                    <div class="form-column">
                                        <!-- Full Name -->
                                        <label for="firstname">Firstname:</label><br>
                                        <input type="text" id="modal_firstname" name="firstname" required class="form-element"><br>
                                    </div>
                                    <div class="form-column">
                                        <!-- Full Name -->
                                        <label for="surname">surname:</label><br>
                                        <input type="text" id="modal_surname" name="surname" required class="form-element"><br>
                                    </div>
                                    <div class="form-column">
                                        <!-- Address -->
                                        <label for="address">Address:</label><br>
                                        <input type="text" id="modal_address" name="address" required class="form-element"><br>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-column">
                                        <!-- Born -->
                                        <label for="born">Born:</label><br>
                                        <input type="date" id="modal_born" name="born" required class="form-element"><br>
                                    </div>
                                    <div class="form-column">
                                        <!-- Died -->
                                        <label for="died">Died:</label><br>
                                        <input type="date" id="modal_died" name="died" required class="form-element"><br>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-column">
                                        <!-- Block Selection -->
                                        <label for="block">Block #:</label><br>
                                        <select id="modal_block" name="block" required class="form-element">
                                            <option value="" disabled selected>Select Block</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select><br>
                                    </div>
                                    <div class="form-column">
                                        <!-- Plot Selection -->
                                        <label for="plot">Plot #:</label><br>
                                        <select id="modal_plot" name="plot" required class="form-element">
                                            <option value="" disabled selected>Select Plot</option>
                                        </select><br>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-column">
                                        <!-- Funeral Day -->
                                        <label for="funeralday">Funeral Day:</label><br>
                                        <input type="date" id="modal_funeralday" name="funeralday" required class="form-element"><br>
                                    </div>
                                    <div class="form-column">
                                        <!-- Date Created -->
                                        <label for="datecreated">Date Created:</label><br>
                                        <input type="date" id="modal_datecreated" name="datecreated" required class="form-element"><br>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button class="button save button-save-modal" onclick="document.getElementById('updateForm').submit()">Save</button>
                        </div>
                    </div>
                </div>

                <script>

                    // TODO: MODAL FUNCTION

                    var addModal = document.getElementById("addModal");
                    var updateModal = document.getElementById("updateModal");

                    function openAddModal() {
                        addModal.style.display = "block";
                    }

                    function closeAddModal() {
                        addModal.style.display = "none";
                    }

                    function openModal(data) {
                    // Set modal field values from the passed data
                    document.getElementById("modal_id").value = data.id;
                    document.getElementById("modal_firstname").value = data.firstname;
                    document.getElementById("modal_surname").value = data.surname;
                    document.getElementById("modal_address").value = data.address;
                    document.getElementById("modal_born").value = data.born;
                    document.getElementById("modal_died").value = data.died;
                    document.getElementById("modal_plot").value = data.plot;
                    document.getElementById("modal_block").value = data.block;
                    document.getElementById("modal_funeralday").value = data.funeralday;

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
                        if (confirm("Are you sure you want to delete this information?")) {
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
                                    url: "searchdeceasedperson.php",
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
                    function fetchAvailablePlots(block, plotDropdownId, selectedPlot = null) {
                        if (block) {
                            var xhr = new XMLHttpRequest();
                            xhr.open("GET", "get_plots.php?block=" + block, true);
                            xhr.onload = function () {
                                if (this.status === 200) {
                                    var plots = JSON.parse(this.responseText);
                                    var plotDropdown = document.getElementById(plotDropdownId);
                                    plotDropdown.innerHTML = '<option value="" disabled>Select Plot</option>'; // Clear previous options

                                    // Populate the dropdown with available plots
                                    plots.forEach(function (plot) {
                                        var option = document.createElement("option");
                                        option.value = plot;
                                        option.textContent = "Plot " + plot;
                                        plotDropdown.appendChild(option);
                                    });

                                    // If there's a selected plot, add it back to the dropdown
                                    if (selectedPlot) {
                                        var oldOption = document.createElement("option");
                                        oldOption.value = selectedPlot;
                                        oldOption.textContent = "Plot " + selectedPlot;
                                        plotDropdown.appendChild(oldOption);
                                        plotDropdown.value = selectedPlot; // Set the selected plot as the current value
                                    }
                                } else {
                                    console.error("Failed to load plots: " + this.status);
                                }
                            };
                            xhr.send();
                        }
                    }

                </script>
            </div>
        </div>
    </div> 
</body>
</html>

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

$firstname = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest';
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';

$sampletotal =200;
$sampleid =0001;


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
                if (!empty($_POST['firstname']) && !empty($_POST['surname']) && !empty($_POST['package']) && !empty($_POST['plotnumber']) && !empty($_POST['blocknumber']) && !empty($_POST['email']) && !empty($_POST['contact'])) {
                    // Insert reservation
                    // $stmt = $pdo->prepare("INSERT INTO reservation (firstname,surname, package, plotnumber, blocknumber, email, contact,province,municipality,completeaddress) VALUES (?,?, ?, ?, ?, ?, ?,?,?,?)");
                    // $stmt->execute([$_POST['firstname'],$_POST['surname'], $_POST['package'], $_POST['plotnumber'], $_POST['blocknumber'], $_POST['email'], $_POST['contact'],$_POST['province'], $_POST['municipality'], $_POST['completeaddress']]);
                    $stmt = $pdo->prepare("INSERT INTO reservation (firstname, surname, package, plotnumber, blocknumber, email, contact, province, municipality, completeaddress) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$_POST['firstname'], $_POST['surname'], $_POST['package'], $_POST['plotnumber'], $_POST['blocknumber'], $_POST['email'], $_POST['contact'], $_POST['province_name'], $_POST['municipality_name'], $_POST['completeaddress']]);

                    // Mark the plot as unavailable
                    $updatePlot = $pdo->prepare("UPDATE plots SET is_available = 0 WHERE plot_number = ? AND block = ?");
                    $updatePlot->execute([$_POST['plotnumber'], $_POST['blocknumber']]);
                } else {
                    echo "All fields are required.";
                }
            } elseif ($action == 'update') {
                if (!empty($_POST['id']) && !empty($_POST['firstname']) && !empty($_POST['surname']) && !empty($_POST['package']) && !empty($_POST['plotnumber']) && !empty($_POST['blocknumber']) && !empty($_POST['email']) && !empty($_POST['contact']) && !empty($_POST['province']) &&!empty($_POST['municipality']) &&!empty($_POST['completeaddress'])) {
                    // First, check if the plot number is changing
                    $reservationStmt = $pdo->prepare("SELECT plotnumber, blocknumber FROM reservation WHERE id = ?");
                    $reservationStmt->execute([$_POST['id']]);
                    $oldReservation = $reservationStmt->fetch(PDO::FETCH_ASSOC);
                    
                    // Update the reservation
                    // $stmt = $pdo->prepare("UPDATE reservation SET firstname = ?, surname = ?, package = ?, plotnumber = ?, blocknumber = ?, email = ?, contact = ?, province = ?,municipality, completeaddress = ? = ? WHERE id = ?");
                    // $stmt->execute([$_POST['firstname'],$_POST['surname'], $_POST['package'], $_POST['plotnumber'], $_POST['blocknumber'], $_POST['email'], $_POST['contact'],$_POST['province'],$_POST[' municipality'],$_POST['completeaddress'],$_POST['id']]);
                    
                    $stmt = $pdo->prepare("UPDATE reservation SET firstname = ?, surname = ?, package = ?, plotnumber = ?, blocknumber = ?, email = ?, contact = ?, province = ?, municipality = ?, completeaddress = ? WHERE id = ?");
                    $stmt->execute([$_POST['firstname'], $_POST['surname'], $_POST['package'], $_POST['plotnumber'], $_POST['blocknumber'], $_POST['email'], $_POST['contact'], $_POST['province_name'], $_POST['municipality_name'], $_POST['completeaddress'], $_POST['id']]);

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
    <title> Reservation</title>
    <link rel="stylesheet" href="./admindashboardcss/adminreserved.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                                            <th>FirstName</th>
                                            <th>SurName</th>
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
                                            <!-- First Name -->
                                            <label for="firstname">First Name:</label><br>
                                            <input type="text" id="add_firstname" name="firstname" required class="form-element"><br><br>

                                            <!-- Surname -->
                                            <label for="surname">Surname:</label><br>
                                            <input type="text" id="add_surname" name="surname" required class="form-element"><br><br>
                                            
                                            <!-- Package -->
                                            <label for="package">Package:</label><br>
                                            <select id="add_package" name="package" required class="form-element">
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
                                            
                                               <!-- Plot Selection -->
                                            <label for="plot">Plot #:</label><br>
                                            <select id="add_plot" name="plotnumber" required class="form-element">
                                                <option value="" disabled selected>Select Plot</option>
                                            </select><br><br>
                                        </div>
                                        
                                        <div class="form-column">
                                          

                                            <!-- Email -->
                                            <label for="email">Email:</label><br>
                                            <input type="email" id="add_email" name="email" required class="form-element"><br><br> 

                                            <!-- Contact -->
                                            <label for="contact">Contact:</label><br>
                                            <input type="text" id="add_contact" name="contact" required class="form-element"><br><br>

                                            <div class="address">
                                                <div class="province">
                                                    <label for="Select Province">Select Province</label>
                                                    <select id="province" name="province" class="form-element" onchange="loadMunicipalities()">
                                                        <option value="">Province</option>
                                                    </select>
                                                </div>
                                                <br>
                                                <div class="municipality">
                                                    <label for="Select Municipality">Select Municipality</label>
                                                    <br>
                                                    <select id="municipality" name="municipality" class="form-element" onchange="toggleAddressFields()">
                                                        <option value=""> Select Municipality</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="barangay" id="barangayDropdown" name="completeaddress" style="display: none;">
                                                <select id="barangay" name="barangay_code" class="form-element" onchange="setAddressFields()">
                                                <!-- Barangay options will be populated here -->
                                                </select>
                                            </div>
                                            <!-- <div class="form-group" id="addressInput" style="display: none;"> -->
                                                <label for="address">Complete Address</label>
                                                <input type="text" id="address" name="completeaddress" maxlength="100" class="form-element" placeholder="">
                                            <!-- </div> -->

                                            <!-- Hidden inputs for province and municipality names -->
                                            <input type="hidden" id="province_name" name="province_name">
                                            <input type="hidden" id="municipality_name" name="municipality_name">
                                            <input type="hidden" id="barangay_name" name="barangay_name">
              
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button class="button-save-modal" onclick="document.getElementById('addForm').submit()">Save</button>
                            </div>
                        </div>
                    </div>

 


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
                                        <label for="firstname">Firstname:</label><br>
                                        <input type="text" id="modal_firstname" name="firstname" class="form-element"><br><br>

                                        <label for="surname">Surname:</label><br>
                                        <input type="text" id="modal_surname" name="surname" class="form-element"><br><br>
                                        
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
                                        
                                        <!-- Plot Selection -->
                                        <label for="plot">Plot #:</label><br>
                                        <select id="modal_plot" name="plotnumber" class="form-element" required>
                                            <option value="" disabled selected>Select Plot</option>
                                        </select><br><br>
                                    </div>
                                    <div class="form-column">
                                    

                                        <!-- Email -->
                                        <label for="email">Email:</label><br>
                                        <input type="email" id="modal_email" name="email" class="form-element"><br><br>
                                        
                                        <!-- Contact -->
                                        <label for="contact">Contact:</label><br>
                                        <input type="text" id="modal_contact" name="contact" class="form-element"><br><br>

                                        <div class="address">
                                            <div class="province">
                                                <!-- Unique ID for the update modal province dropdown -->
                                                <label for="updateProvince">Select Province</label>
                                                <select id="updateProvince" name="province" class="form-element" onchange="loadMunicipalitiesForUpdate()">
                                                    <option value="">Select Province</option>
                                                </select>
                                            </div>
                                            <br>
                                            <div class="municipality">
                                                <!-- Unique ID for the update modal municipality dropdown -->
                                                <label for="updateMunicipality">Select Municipality</label>
                                                <br>
                                                <select id="updateMunicipality" name="municipality" class="form-element" onchange="toggleAddressFieldsForUpdate()">
                                                    <option value="">Select Municipality</option>
                                                </select>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="barangay" id="updateBarangayDropdown" name="completeaddress" style="display: none;">
                                            <select id="updateBarangay" name="barangay_code" class="form-element" onchange="setAddressFieldsForUpdate()">
                                            <!-- Barangay options will be populated here -->
                                            </select>
                                        </div>
                                    
                                        <!-- <div class="form-group" id="updateAddressInput" style="display: none;"> -->
                                            <label for="updateAddress">Complete Address</label>
                                            <input type="text" id="updateAddress" name="completeaddress" maxlength="100" class="form-element" placeholder="">  
                                        

                                        <!-- Hidden inputs for province and municipality names -->
                                        <!-- Hidden inputs for province and municipality names and codes -->
                                        <input type="hidden" id="updateProvinceName" name="province_name">
                                        <input type="hidden" id="updateMunicipalityName" name="municipality_name">
                                        <input type="hidden" id="updateProvinceCode" name="province_code">
                                        <input type="hidden" id="updateMunicipalityCode" name="municipality_code">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button class="button update" onclick="document.getElementById('updateForm').submit()">Save</button>
                        </div>
                    </div>
                </div>

                <!-- TODO:Payment Modal and Payment Proof Upload -->
                <div id="paymentModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closePaymentModal()">&times;</span>
                        <div class="modal-header-payment">Payment</div>
                        <form action="process_payment.php" method="post" enctype="multipart/form-data">
                            <div class="total-amount1">
                                <p>Total Payment:</p>
                                <p id="modal-price">&#x20B1; 0.00</p> <!-- Placeholder for dynamic price -->
                            </div>
                            <br>
                            <div class="name-info">
                                <div class="name-client" id="client-name"></div>
                                <div class="client-id" id="client-id" style="float: right;"></div> <!-- ID with float right -->
                                <div class="or-number-info">
                                    <p><span id="or-number"></span></p>
                                </div>
                            </div>  
                            <div class="client-info">
                                <div class="client-package" id="client-package"></div>
                                <div class="client-block" id="client-block"></div>
                                <div class="client-plot" id="client-plot"></div>
                            </div>
                            <div class="total-amount">
                                <?php echo date("Y/m/d"); ?>
                                <p id="payment-status">Payment Status: Not Paid</p>
                            </div>

                            <!-- Hidden inputs for reservation data -->
                            <input type="hidden" name="reservation_id" id="reservation-id">
                            <input type="hidden" name="firstname" id="firstname">
                            <input type="hidden" name="surname" id="surname">
                            <input type="hidden" name="package" id="package">
                            <input type="hidden" name="block" id="block">
                            <input type="hidden" name="plot" id="plot">

                            <div class="payment-option">
                                <label for="payment-method">Select Payment Method:</label>
                                <select name="payment_method" id="payment-method" class="form-input" required onchange="toggleGcashInfo()">
                                    <option value="cash">Cash</option>
                                    <option value="gcash">GCash</option>
                                </select>
                            </div>
                            
                            <!-- GCash Information -->
                            <div id="gcash-info" style="display: none;">
                                <p>Desiree Leal</p>
                                <p>09653384884</p>
                                <br>
                                <input type="file" id="file-upload" name="payment_proof" class="upload-container" title="Upload proof of payment">
                            </div>

                            <div class="payment-radio">
                                <br>
                                <input type="radio" id="cash-radio" name="installment_plan" value="fullpayment" checked>
                                <label for="cash-radio">Full Payment</label>
                                <br>
                                <input type="radio" id="gcash-radio" name="installment_plan" value="installment">
                                <label for="gcash-radio">Installment</label>
                            </div>
                            
                            <div class="radio-term" style="display: none;">
                                <div class="radio-group">
                                    <label>
                                        <input type="radio" name="duration" value="6months">
                                        6 Months - <span id="installment-price-6">₱ 0.00</span>
                                    </label>
                                    <br>
                                    <label>
                                        <input type="radio" name="duration" value="9months">
                                        9 Months - <span id="installment-price-9">₱ 0.00</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="payment-button">
                                <button type="submit" class="form-payment-button">Proceed to Payment</button>
                            </div>
                        </form>
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
                                document.getElementById("modal_firstname").value = data.firstname;
                                document.getElementById("modal_surname").value = data.surname;
                                document.getElementById("modal_package").value = data.package;
                                document.getElementById("modal_plot").value = data.plotnumber;
                                document.getElementById("modal_block").value = data.blocknumber;
                                document.getElementById("modal_email").value = data.email;
                                document.getElementById("modal_contact").value = data.contact;
                                
                                // Set province and municipality values
                                document.getElementById("province").value = data.province;
                                document.getElementById("municipality").value = data.municipality;
                                
                                // You might need to manually trigger the events to load the municipality and barangay dropdowns.
                                loadMunicipalities();  // This will populate the municipalities based on the province
                                loadBarangays(); // This will populate barangays based on the municipality

                                 // Set the province and municipality values
                                document.getElementById("updateProvince").value = data.province_code; // Set province code to the dropdown
                                document.getElementById("updateMunicipality").value = data.municipality_code; // Set municipality code to the dropdown

                                // Ensure the selected options are highlighted
                                document.getElementById("address").value = data.completeaddress;


                                // Load provinces and set province and municipality
                                loadProvincesForUpdate(data.province_code, data.municipality_code);
                                
                                // Set complete address input if provided
                                document.getElementById("completeAddress").value = data.completeaddress || '';
                                
                                
                                updateModal.style.display = "block";
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
                        
                          // TODO: update button close the modal
                          function closeModal() {
                                const updateModal = document.getElementById("updateModal");
                                if (updateModal) {
                                    updateModal.style.display = "none";
                                }
                            }


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

                    // TODO: payment modal functionality
                    function openPaymentModal(reservationId, firstname, surname, package, block, plot) {
                        // Set the values in the modal
                        document.getElementById('reservation-id').value = reservationId;
                        document.getElementById('firstname').value = firstname;
                        document.getElementById('surname').value = surname;
                        document.getElementById('package').value = package;
                        document.getElementById('block').value = block;
                        document.getElementById('plot').value = plot;
                        
                        // Set the modal text content for display (can be customized)
                        document.getElementById('client-name').textContent = `${firstname} ${surname}`;
                        document.getElementById('client-package').textContent = `Package: ${package}`;
                        document.getElementById('client-block').textContent = `Block: ${block}`;
                        document.getElementById('client-plot').textContent = `Plot: ${plot}`;
                        
                        // Set the OR number (reservation ID)
                        document.getElementById('or-number').textContent = `OR-${reservationId}`;
                        
                        // Define the package prices
                        let price = 0;
                        switch(package.toLowerCase()) {
                            case 'lawn':
                                price = 20000;
                                break;
                            case 'garden':
                                price = 30000;
                                break;
                            case 'family_state':
                                price = 50000;
                                break;
                            default:
                                price = 0; // Default case in case the package is not recognized
                        }

                        // Set the total price for full payment
                        document.getElementById('modal-price').textContent = '₱ ' + price.toFixed(2);
                        
                        // Show or hide installment options based on full payment selection
                        const installmentRadio = document.querySelector('input[name="installment_plan"][value="installment"]');
                        const fullPaymentRadio = document.querySelector('input[name="installment_plan"][value="fullpayment"]');

                        if (fullPaymentRadio.checked) {
                            // If "Full Payment" is selected, show the full amount
                            document.getElementById('modal-price').textContent = '₱ ' + price.toFixed(2);
                        }

                        // Open the modal
                        document.getElementById('paymentModal').style.display = 'block';
                    }

                    document.querySelectorAll('input[name="installment_plan"]').forEach(radio => {
                        radio.addEventListener('change', function() {
                            if (this.value === 'installment') {
                                // Show installment options
                                document.querySelector('.radio-term').style.display = 'block';
                            } else {
                                // Hide installment options for full payment
                                document.querySelector('.radio-term').style.display = 'none';
                            }
                        });
                    });


                    document.querySelectorAll('input[name="duration"]').forEach(radio => {
                        radio.addEventListener('change', function() {
                            let months = this.value === '6months' ? 6 : 9;
                            let monthlyAmount = price / months;
                            
                            // Display the installment price for selected duration
                            document.getElementById(`installment-price-${months}`).textContent = `₱ ${monthlyAmount.toFixed(2)} per month`;
                        });
                    });


                    // TODO: function to display the gcash info
                     // Function to show/hide GCash info based on payment method selection
                    function toggleGcashInfo() {
                        var paymentMethod = document.getElementById("payment-method").value;
                        var gcashInfo = document.getElementById("gcash-info");

                        if (paymentMethod === "gcash") {
                            gcashInfo.style.display = "block"; // Show GCash info
                        } else {
                            gcashInfo.style.display = "none"; // Hide GCash info
                        }
                    }

                    // Function to close the payment modal
                    function closePaymentModal() {
                        document.getElementById("paymentModal").style.display = "none";
                    }

                    // If the modal is already open and GCash is selected, show the GCash info
                    window.onload = function() {
                        toggleGcashInfo(); // Call to ensure correct display on page load
                    };



                    // TODO: upload file functionality
                    const fileUpload = document.getElementById('file-upload');
                    const fileName = document.getElementById('file-name');

                    fileUpload.addEventListener('change', function() {
                        if (fileUpload.files.length > 0) {
                            fileName.textContent = fileUpload.files[0].name;
                        } else {
                            fileName.textContent = 'No file chosen';
                        }
                    });

                    // TODO: ADDRESS API
                    // Main dropdown elements
                    const provinceDropdown = document.getElementById('province');
                    const municipalityDropdown = document.getElementById('municipality');
                    const barangayDropdown = document.getElementById('barangay');
                    const addressInput = document.getElementById('addressInput');

                    // Hidden inputs for address names
                    const provinceNameInput = document.getElementById('province_name');
                    const municipalityNameInput = document.getElementById('municipality_name');
                    const barangayNameInput = document.getElementById('barangay_name');

                    // Update modal elements
                    const updateProvinceDropdown = document.getElementById('updateProvince');
                    const updateMunicipalityDropdown = document.getElementById('updateMunicipality');
                    const updateProvinceNameInput = document.getElementById('updateProvinceName'); // Hidden input for province name
                    const updateMunicipalityNameInput = document.getElementById('updateMunicipalityName'); // Hidden input for municipality name

                    // Helper function to fetch data from API
                    async function fetchAPI(url) {
                        try {
                            const response = await fetch(url);
                            if (!response.ok) throw new Error('Network response was not ok');
                            return await response.json();
                        } catch (error) {
                            console.error('Fetch error:', error);
                            return [];
                        }
                    }

                    // Function to load provinces for main form and update modal
                    async function loadProvinces(dropdown, selectedCode = null) {
                        const provinces = await fetchAPI('https://psgc.gitlab.io/api/provinces/');
                        dropdown.innerHTML = '<option value="">Select Province</option>';
                        provinces.forEach(province => {
                            const option = document.createElement('option');
                            option.value = province.code;
                            option.textContent = province.name;
                            dropdown.appendChild(option);
                        });
                        if (selectedCode) dropdown.value = selectedCode;
                    }

                    // Load municipalities based on selected province
                    async function loadMunicipalities(dropdown, provinceDropdown, provinceNameInput, selectedCode = null, municipalityNameInput = null) {
                        const provinceCode = provinceDropdown.value;
                        provinceNameInput.value = provinceDropdown.options[provinceDropdown.selectedIndex]?.text || ''; // Set province name
                        if (!provinceCode) return;

                        const municipalities = await fetchAPI(`https://psgc.gitlab.io/api/provinces/${provinceCode}/cities-municipalities/`);
                        dropdown.innerHTML = '<option value="">Select Municipality</option>';
                        municipalities.forEach(municipality => {
                            const option = document.createElement('option');
                            option.value = municipality.code;
                            option.textContent = municipality.name;
                            dropdown.appendChild(option);
                        });
                        if (selectedCode) dropdown.value = selectedCode;

                        // Set the municipality name if municipalityNameInput is provided (for update modal)
                        if (municipalityNameInput && selectedCode) {
                            municipalityNameInput.value = dropdown.options[dropdown.selectedIndex]?.text || '';
                        }
                    }

                    // Function to load provinces in the update modal
                    async function loadProvincesForUpdate(provinceCode, municipalityCode) {
                        await loadProvinces(updateProvinceDropdown, provinceCode); // Load provinces with selected province
                        if (provinceCode) {
                            await loadMunicipalities(updateMunicipalityDropdown, updateProvinceDropdown, updateProvinceNameInput, municipalityCode, updateMunicipalityNameInput); // Load municipalities with selected municipality
                        }
                    }

                    // Event listeners for dropdowns
                    provinceDropdown.addEventListener('change', () => loadMunicipalities(municipalityDropdown, provinceDropdown, provinceNameInput));
                    updateProvinceDropdown.addEventListener('change', () => loadMunicipalities(updateMunicipalityDropdown, updateProvinceDropdown, updateProvinceNameInput, null, updateMunicipalityNameInput));

                    // Update the municipality name for the update modal when the municipality is selected
                    updateMunicipalityDropdown.addEventListener('change', () => {
                        updateMunicipalityNameInput.value = updateMunicipalityDropdown.options[updateMunicipalityDropdown.selectedIndex]?.text || '';
                    });

                    // Open modal function to set initial values
                    function openModal(data) {
                        document.getElementById("modal_id").value = data.id;
                        document.getElementById("modal_firstname").value = data.firstname;
                        document.getElementById("modal_surname").value = data.surname;
                        document.getElementById("modal_package").value = data.package;
                        document.getElementById("modal_plot").value = data.plotnumber;
                        document.getElementById("modal_block").value = data.blocknumber;
                        document.getElementById("modal_email").value = data.email;
                        document.getElementById("modal_contact").value = data.contact;
                        
                        // Load provinces and municipalities for the update modal
                        loadProvincesForUpdate(data.province_code, data.municipality_code);
                        
                        // Set address input if provided
                        document.getElementById("updateAddress").value = data.completeaddress || '';
                        
                        // Display the update modal
                        updateModal.style.display = "block";
                    }

                    // Initialize provinces on page load
                    document.addEventListener("DOMContentLoaded", () => {
                        loadProvinces(provinceDropdown);
                    });
                    </script>
                </div>
            </div>
        </div>
    </div>
</body>
</html>


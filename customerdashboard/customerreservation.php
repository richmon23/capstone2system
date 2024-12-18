<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = $_SESSION['user_id'];
$firstname = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest';
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';

// Include the database connection
require_once '../connection/connection.php';

try {
    // Database connection settings
    $dsn = 'mysql:host=localhost;dbname=capstone2db';
    $username = 'root';
    $password = '';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    // Create a PDO instance (connect to the database)
    $pdo = new PDO($dsn, $username, $password, $options);

    // Handle form submission to save reservation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $surname = $_POST['surname'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $package = $_POST['packages'];
    $plotnumber = $_POST['plotnumber'];
    $blocknumber = $_POST['blocknumber'];


    // SQL query to insert data into the reservation table
    $sql = "INSERT INTO reservation (firstname,surname, address, contact, email, package, plotnumber, blocknumber, user_id) 
            VALUES (:firstname, :surname, :address, :contact, :email, :package, :plotnumber, :blocknumber, :user_id)";

    // Prepare the SQL statement
    $stmt = $pdo->prepare($sql);

    // Bind values to the SQL query
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':surname', $surname);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':package', $package);
    $stmt->bindParam(':plotnumber', $plotnumber);
    $stmt->bindParam(':blocknumber', $blocknumber);
    $stmt->bindParam(':user_id', $user_id);

    // Execute the query
    $stmt->execute();

    // Now update the plot availability to mark it as taken
    $sqlUpdatePlot = "UPDATE plots SET is_available = 0 WHERE plot_number = :plotnumber";
    $stmtUpdate = $pdo->prepare($sqlUpdatePlot);
    $stmtUpdate->bindParam(':plotnumber', $plotnumber);
    $stmtUpdate->execute();

    echo "Reservation successfully saved!";
}
    // SQL query to fetch only reservations for the current logged-in user
    $sqlFetch = "SELECT * FROM reservation WHERE user_id = :user_id";
    $stmtFetch = $pdo->prepare($sqlFetch);
    $stmtFetch->bindParam(':user_id', $user_id);
    $stmtFetch->execute();
    $reservations = $stmtFetch->fetchAll();

    // SQL query to fetch available plots (where is_available = 1)
    $sqlPlots = "SELECT plot_number FROM plots WHERE is_available = 1";
    $stmtPlots = $pdo->prepare($sqlPlots);
    $stmtPlots->execute();
    $availablePlots = $stmtPlots->fetchAll();

    // SQL query to fetch distinct blocks
    $sqlBlocks = "SELECT DISTINCT block FROM plots WHERE is_available = 1";
    $stmtBlocks = $pdo->prepare($sqlBlocks);
    $stmtBlocks->execute();
    $availableBlocks = $stmtBlocks->fetchAll();

    if (isset($_GET['block'])) {
        $block = $_GET['block'];
    
        // Query to fetch available plots based on the selected block
        $sql = "SELECT plot_number FROM plots WHERE block = :block AND is_available = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':block', $block);
        $stmt->execute();
        $availablePlots = $stmt->fetchAll();
    
        echo json_encode($availablePlots); // Send the results back as JSON
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


 // Fetch user profile picture from the database
 $userId = $_SESSION['user_id'];
 $stmt = $conn->prepare("SELECT profile_pic FROM users WHERE id = :id");
 $stmt->bindParam(':id', $userId);
 $stmt->execute();
 $user = $stmt->fetch(PDO::FETCH_ASSOC);
 
 // Check if user profile picture exists
 $profilePic = !empty($user['profile_pic']) ? $user['profile_pic'] : 'default.png'; // Use a default image if none is found

 $sqlFetch = "SELECT * FROM reservation WHERE user_id = :user_id";
//  echo "<pre>";
//  var_dump($reservations);
//  echo "</pre>";
 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reservation </title>
    <link rel="stylesheet" href="./customerdashboard_css/customerreservation.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head> 
<style>
    .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 60%;
    border-radius: 8px;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover, .close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

</style>
<body>

    <!-- <a href="logout.php">Logout</a> -->

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
                                    <span>RESERVATION
                                    </span>
                                    <!-- <div class="search-box">
                                        <i class="fas fa-search search-icon"></i>
                                        <input type="text" class="search-input" placeholder="Search">
                                    </div> -->
                                </div>
                            </div>
                            <div class="right-content2">
                            <div class="left-aside">
                                <div class="addbtn">
                                    <!-- Button to open the modal -->
                                    <button class="add"><i class="fas fa-plus"></i></button>
                                </div>

                   <!-- Display the reservation cards -->
                        <div class="view-reservation">
                            <div class="reservation-container">
                                <?php if (!empty($reservations)): ?>
                                    <?php foreach ($reservations as $reservation): ?>
                                        <div class="reservation-card">
                                            <p><strong>Firstname:</strong> <?php echo htmlspecialchars($reservation['firstname']); ?></p>
                                            <p><strong>Surname:</strong> <?php echo htmlspecialchars($reservation['surname']); ?></p>
                                            <p><strong>Address:</strong> <?php echo htmlspecialchars($reservation['address']); ?></p>
                                            <p><strong>Contact:</strong> <?php echo htmlspecialchars($reservation['contact']); ?></p>
                                            <p><strong>Email:</strong> <?php echo htmlspecialchars($reservation['email']); ?></p>
                                            <p><strong>Package:</strong> <?php echo htmlspecialchars($reservation['package']); ?></p>
                                            <p><strong>Plot Number:</strong> <?php echo htmlspecialchars($reservation['plotnumber']); ?></p>
                                            <p><strong>Block Number:</strong> <?php echo htmlspecialchars($reservation['blocknumber']); ?></p>
                                            <p><strong>Status:</strong> <?php echo htmlspecialchars($reservation['status']); ?></p>

                                            <!-- Display "Add Payment" button if status is 'approved' -->
                                            <?php if (strtolower($reservation['status']) === 'approved'): ?>
                                                <button class="add-payment-btn" data-reservation-id="<?php echo $reservation['id']; ?>">ADD PAYMENT</button>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="no-reservation-found">No reservations found.</p>
                                <?php endif; ?>
                            </div>
                        </div>


                <!-- Modal Structure -->
                <div id="myModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2>Add Reservation </h2>
                            <form method="POST">
                                <input type="text" class="input" id="firstname" name="firstname" required placeholder="Firstname">
                                <input type="text" class="input" id="surname" name="surname" required placeholder="Surname">
                                <input type="text" class="input" id="address" name="address" required placeholder="Address">
                                <input type="text" class="input" id="contact" name="contact" required placeholder="Contact">
                                <input type="text" class="input" id="email" name="email" required placeholder="Email">
                                <br>
                                <div class="content">
                                    <div class="packages">
                                        <select id="packages" name="packages">
                                            <option value="" disabled selected>Packages</option>
                                            <option value="lawn">Lawn</option>
                                            <option value="garden">Garden</option>
                                            <option value="family State">Family State</option>
                                        </select>
                                    </div>

                                    <div class="block">
                                        <select id="blocks" name="blocknumber" onchange="fetchAvailablePlots(this.value, 'plot')">
                                            <option value="" disabled selected>Blocks</option>
                                            <?php foreach ($availableBlocks as $block): ?>
                                                <option value="<?php echo htmlspecialchars($block['block']); ?>"><?php echo htmlspecialchars($block['block']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="plot">
                                        <select id="plot" name="plotnumber">
                                            <option value="" disabled selected>Select Plot</option>
                                            <!-- Plot options will be populated here -->
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="form-btn">
                                    <button type="submit" class="btnsubmit">Submit</button>
                                    <button type="reset" class="btncancel">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 

                   <!-- Payment Modal -->
<div id="paymentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header-payment">Payment</div>
        <span class="close" id="closeModal">&times;</span>
        <form action="process_payment.php" method="post" enctype="multipart/form-data">
            <div class="total-amount1">
                <p>Total Payment:</p>
                <p id="modal-price">&#x20B1; 0.00</p>
            </div>
            <br>
            <div class="name-info">
                <div class="name-client" id="client-name"></div>
                <div class="client-id" id="client-id" style="float: right;"></div>
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



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Get modal element
        var modal = document.getElementById("myModal");

        // Get the button that opens the modal
        var btn = document.querySelector(".add");

        // Get the <span> element that closes the modal
        var span = document.querySelector(".close");

        // When the user clicks the button, open the modal 
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function fetchAvailablePlots(block, plotDropdownId) {
    console.log("Fetching plots for block: " + block); // Add this line
    if (block) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "customer_get_plots.php?block=" + block, true);
        xhr.onload = function() {
            console.log("AJAX Response: " + xhr.responseText); // Log AJAX response
            if (xhr.status === 200) {
                var plotDropdown = document.getElementById(plotDropdownId);
                plotDropdown.innerHTML = ''; // Clear existing options
                var plots = JSON.parse(xhr.responseText);
                // Populate the dropdown with available plots
                if (plots.length > 0) {
                    plots.forEach(function(plot) {
                        var option = document.createElement("option");
                        option.value = plot.plot_number;
                        option.textContent = plot.plot_number;
                        plotDropdown.appendChild(option);
                    });
                } else {
                    var option = document.createElement("option");
                    option.value = "";
                    option.textContent = "No plots available";
                    plotDropdown.appendChild(option);
                }
            }
        }
        xhr.send();
    }


  
// Function to open the payment modal
function openPaymentModal(reservationId, firstname, surname, package, block, plot) {
    // Set values in the modal
    document.getElementById('reservation-id').value = reservationId;
    document.getElementById('firstname').value = firstname;
    document.getElementById('surname').value = surname;
    document.getElementById('package').value = package;
    document.getElementById('block').value = block;
    document.getElementById('plot').value = plot;

    // Set the modal text content for display
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

// Close modal function
document.getElementById('closeModal').addEventListener('click', function () {
    document.getElementById('paymentModal').style.display = 'none';
});

// Button click to trigger modal
document.querySelectorAll('.add-payment-btn').forEach(button => {
    button.addEventListener('click', function () {
        const reservationId = this.getAttribute('data-reservation-id');

        // Fetch reservation details dynamically (this part can be improved with AJAX or prefilled data)
        const reservationData = {
            id: reservationId,
            firstname: "John",  // Replace with actual data
            surname: "Doe",     // Replace with actual data
            package: "Lawn",    // Replace with actual data
            block: "2",         // Replace with actual data
            plot: "15"          // Replace with actual data
        };

        // Open the modal with dynamic data
        openPaymentModal(
            reservationData.id, 
            reservationData.firstname, 
            reservationData.surname, 
            reservationData.package, 
            reservationData.block, 
            reservationData.plot
        );
    });
});

// Toggle GCash Info based on payment method selection
function toggleGcashInfo() {
    const paymentMethod = document.getElementById("payment-method").value;
    const gcashInfo = document.getElementById("gcash-info");
    if (paymentMethod === "gcash") {
        gcashInfo.style.display = "block";
    } else {
        gcashInfo.style.display = "none";
    }
}

// If the modal is already open and GCash is selected, show the GCash info
window.onload = function() {
    toggleGcashInfo(); // Ensure correct display on page load
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
}

    </script>

</body>
</html>


                     
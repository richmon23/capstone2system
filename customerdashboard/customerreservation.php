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
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $package = $_POST['packages'];
    $plotnumber = $_POST['plotnumber'];
    $blocknumber = $_POST['blocknumber'];

    // SQL query to insert data into the reservation table
    $sql = "INSERT INTO reservation (fullname, address, contact, email, package, plotnumber, blocknumber, user_id) 
            VALUES (:fullname, :address, :contact, :email, :package, :plotnumber, :blocknumber, :user_id)";

    // Prepare the SQL statement
    $stmt = $pdo->prepare($sql);

    // Bind values to the SQL query
    $stmt->bindParam(':fullname', $fullname);
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
<body>

    <!-- <a href="logout.php">Logout</a> -->

    <div class="row">
        <div class="left-content col-4">
            <div class="memoriallogo"><img src="../images/bogomemoriallogo.png" alt="bogomemoriallogo"></div>
            <div class="hamburgermenu"><img src="../images/hamburgermenu.png" alt="hamburgermenu"></div> 
            <div class="adminprofile">
                <center><img src="../images/female.png" alt="adminicon">
                <!-- <h2><?php echo $firstname; ?></h2></center> -->
            </div>
            <center>
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
            </center>
        </div>
        <div class="main">
            <div class="right-content1">
                <div class="right-header col-9"> 
                    <span class="header"><h1>Reservation</h1></span>
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
                                    <h3><?php echo htmlspecialchars($reservation['fullname']); ?></h3>
                                    <p><strong>Address:</strong> <?php echo htmlspecialchars($reservation['address']); ?></p>
                                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($reservation['contact']); ?></p>
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($reservation['email']); ?></p>
                                    <p><strong>Package:</strong> <?php echo htmlspecialchars($reservation['package']); ?></p>
                                    <p><strong>Plot Number:</strong> <?php echo htmlspecialchars($reservation['plotnumber']); ?></p>
                                    <p><strong>Block Number:</strong> <?php echo htmlspecialchars($reservation['blocknumber']); ?></p>
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
                                <input type="text" class="input" id="name" name="fullname" required placeholder="Name">
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
}

    </script>

</body>
</html>


                     
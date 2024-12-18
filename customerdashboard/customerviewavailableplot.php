
<?php

    
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

    $firstname = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest';
    $email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';

   
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    

    // Database connection details
    $host = 'localhost'; // Change as needed
    $db = 'capstone2db'; // Replace with your database name
    $user = 'root'; // Replace with your username
    $pass = ''; // Replace with your password
    
    try {
        // Create a new PDO connection
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // Fetch all plots from the table
        $stmt = $conn->prepare("SELECT plot_id, block, plot_number, is_available FROM plots");
        $stmt->execute();
    
        // Check if any records are found
        if ($stmt->rowCount() > 0) {
            // Fetch the data as an associative array
            $plots = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $plots = []; // No records found
        }
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
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
    <title>Plot Availability Map </title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="customeravailableplot.css">
    <style>
         .map-container2{
            display: flex;
            gap:15px;
            margin-left:7%;
            color: white;
        }
        .availalble{
            background-color: blue;
            width: 100px;
            height: 15px;
        }
        .notavailable {
            background-color: red;
            width: 100px;
            height: 15px;
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
                   <span>Available Plot & Block </h2>
                   </span>
                   <!-- <a href=""><img src="../images/file.png" alt="" class="paymenthistory"></a> -->
                    <br>
                    <br>
                    <br>
                    <!-- <h3 class="rightsidebar-content2" > Choose Payment Method:</h3> -->
                   
                   </div>
                </div>
                
                <div class="right-content2">
                    <div class="map-container2">
                        Available: <div class="availalble"></div>
                        Not Available:<div class="notavailable"></div>
                    </div>
                    <div class="right-header col-9">
                        <div id="map"></div>
                    </div>  
               </div>
            </div>
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <script>
        // Initialize the map
        var map = L.map('map').setView([11.043601457994624, 123.98979557034839], 19);

        // Add a tile layer to the map (using OpenStreetMap tiles)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // Base coordinates for blocks
        var blockCoordinates = {
            '1': { lat: 11.0435698361405, lng: 123.98982242330341 },
            '2': { lat: 11.043717289687812, lng: 123.99016571516783 },
            '3': { lat: 11.043196046708548, lng: 123.98990822311953 },
            '4': { lat: 11.043190781623228, lng: 123.99015498633248 }
        };

        // Function to generate plot coordinates within a block using a grid layout
        function generatePlotCoordinates(block, plotNumber) {
            var base = blockCoordinates[block];
            var row = Math.floor((plotNumber - 1) / 5); // 5 columns
            var col = (plotNumber - 1) % 5; // Change to 5 for number of plots per column

            // Adjust these values to control the padding between plots
            var latOffset = row * 0.000055; // Increase to add vertical space between plots
            var lngOffset = col * 0.000055; // Increase to add horizontal space between plots

            return [base.lat + latOffset, base.lng + lngOffset];
        }

        // Sample plot data (from PHP)
        var plots = <?php echo json_encode($plots); ?>;

        // Debugging: Log the fetched plots to the console
        console.log(plots);

        // Add markers to the map based on availability
        plots.forEach(function(plot) {
            var coordinates = generatePlotCoordinates(plot.block, plot.plot_number);
            var markerColor = plot.is_available === '1' ? 'blue' : 'red'; // Change color based on availability

            // Custom marker with size 5px
            var marker = L.circleMarker(coordinates, {
                color: markerColor,
                radius: 5, // Set marker size to 5px
                fillOpacity: 0.5,
                weight: 2 // Border thickness
            }).addTo(map);

            // Optional: Add popups to each marker
            marker.bindPopup('Block: ' + plot.block + ', Plot Number: ' + plot.plot_number + ', Available: ' + (plot.is_available === '1' ? 'Yes' : 'No'));
        });

        // Function to locate and navigate to a plot
        function locatePlot() {
            var block = document.getElementById('block-number').value;
            var plotNumber = document.getElementById('plot-number').value;

            if (!blockCoordinates[block] || plotNumber < 1 || plotNumber > 50) {
                alert("Invalid block or plot number");
                return;
            }

            var plotCoordinates = generatePlotCoordinates(block, plotNumber);
            
            // Add a marker at the plot location
            var plotMarker = L.marker(plotCoordinates).addTo(map)
                .bindPopup("Plot " + plotNumber + " in Block " + block)
                .openPopup();

            // Use Geolocation API to get the user's current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var currentLocation = [position.coords.latitude, position.coords.longitude];

                    // Add a marker for the current location
                    L.marker(currentLocation).addTo(map).bindPopup("You are here").openPopup();

                    // Use Leaflet Routing Machine to draw a route from current location to the plot
                    var routeControl = L.Routing.control({
                        waypoints: [
                            L.latLng(currentLocation),
                            L.latLng(plotCoordinates)
                        ],
                        routeWhileDragging: true
                    }).addTo(map);

                    // Listen for routing events and provide voice navigation
                    routeControl.on('routesfound', function(e) {
                        var routes = e.routes;
                        var instructions = routes[0].instructions;

                        // Function to speak text using Web Speech API
                        function speak(text) {
                            var msg = new SpeechSynthesisUtterance();
                            msg.text = text;
                            window.speechSynthesis.speak(msg);
                        }

                        // Loop through the instructions and give voice directions
                        instructions.forEach(function(instruction, i) {
                            setTimeout(function() {
                                speak(instruction.text); // Speak out the instruction
                            }, i * 5000); // Delay between each instruction
                        });
                    });
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }
</Script>
</body>
</html>


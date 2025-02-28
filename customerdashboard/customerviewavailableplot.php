
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
        var map = L.map('map').setView([11.043601457994624, 123.98979557034839], 19);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        var blockCoordinates = {
            '1': { lat: 11.04350923890096, lng: 123.98980085290329 },
            '2': { lat: 11.04351919270268, lng: 123.99017281573597 },
            '3': { lat: 11.043146307745978, lng: 123.98983080964149 },
            '4': { lat: 11.043140747297683, lng: 123.99013973786977 }
        };

        function generatePlotCoordinates(block, plotNumber) {
            var base = blockCoordinates[block];
            var row = Math.floor((plotNumber - 1) / 5);
            var col = (plotNumber - 1) % 5;
            var latOffset = row * 0.000055;
            var lngOffset = col * 0.000055;
            return [base.lat + latOffset, base.lng + lngOffset];
        }

        var plots = <?php echo json_encode($plots); ?>;

        plots.forEach(function (plot) {
            var coordinates = generatePlotCoordinates(plot.block, plot.plot_number);
            var markerColor = plot.is_available == '1' ? 'blue' : 'red';

            L.circleMarker(coordinates, {
                color: markerColor,
                radius: 5,
                fillOpacity: 0.5,
                weight: 2
            }).addTo(map).bindPopup('Block: ' + plot.block + ', Plot Number: ' + plot.plot_number + ', Available: ' + (plot.is_available == '1' ? 'Yes' : 'No'));
        });

        function locatePlot() {
            var block = document.getElementById('block-number').value;
            var plotNumber = document.getElementById('plot-number').value;

            if (!blockCoordinates[block] || plotNumber < 1 || plotNumber > 50) {
                alert("Invalid block or plot number");
                return;
            }

            var plotCoordinates = generatePlotCoordinates(block, plotNumber);

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var currentLocation = [position.coords.latitude, position.coords.longitude];

                    map.setView(currentLocation, 19);

                    L.marker(currentLocation).addTo(map).bindPopup("You are here").openPopup();
                    L.marker(plotCoordinates).addTo(map).bindPopup("Plot " + plotNumber + " in Block " + block).openPopup();

                    var routeControl = L.Routing.control({
                        waypoints: [L.latLng(currentLocation), L.latLng(plotCoordinates)],
                        routeWhileDragging: true
                    }).addTo(map);

                    routeControl.on('routesfound', function (e) {
                        var routes = e.routes;
                        if (routes.length === 0) return;
                        var instructions = routes[0].instructions;

                        function speak(text) {
                            var msg = new SpeechSynthesisUtterance(text);
                            window.speechSynthesis.speak(msg);
                        }

                        if (instructions.length > 0) speak(instructions[0].text);

                        instructions.forEach(function (instruction, i) {
                            setTimeout(function () {
                                speak(instruction.text);
                            }, i * 5000);
                        });
                    });
                }, function (error) {
                    console.error("Geolocation error:", error);
                    alert("Unable to retrieve your location. Error: " + error.message);
                }, {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }
    </script>
</body>
</html>


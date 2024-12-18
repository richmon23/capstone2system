<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plot Availability Map with Voice Navigation</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
       #map {
            height: 600px;
            width: 100%;
        }

        #search-box {
            margin: 20px;
            text-align: center;
            width:100%;
        }

        h3 {
            text-align: center;
        }

        input {
            margin: 5px;
            padding: 8px;
            width: 200px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        } 

        .map-container1{
            display: flex;
            margin-top: 10px;
            gap:10px;
            width: 100%;
           
        }
        .map-container2{
            display: flex;
            gap:15px;
            margin-left:7%;
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
        .button-back{
           margin-right:200px;
        }
    </style>
</head>

<body>
    <h3>Plot Availability Map with Voice Navigation</h3>
    <div id="search-box">
        <input type="text" id="block-number" placeholder="Enter Block (1-4)">
        <input type="number" id="plot-number" placeholder="Enter Plot Number (1-50)">
        <button onclick="locatePlot()">Locate Plot</button>
       <div class="map-container1">
        <div class="button-back">
                <a href="customerdashboard.php"><button><i class="fas fa-arrow-left"></i> Back to Main</button></a>    
            </div>
            <div class="map-container2">
                Available: <div class="availalble"></div>
                Not Available:<div class="notavailable"></div>
            </div>
         </div>
        
    </div>

    <div id="map"></div>

    <?php
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
    ?>

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

    // Use Geolocation API to get the user's current location with high accuracy
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var currentLocation = [position.coords.latitude, position.coords.longitude];

            console.log("Accurate Current Location:", currentLocation); // Debugging

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

                // Debugging: Check instructions
                console.log("Routing Instructions:", instructions); // Debugging

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
        }, function(error) {
            console.error("Geolocation error:", error); // Log any errors with more detail
            alert("Unable to retrieve your location. Error: " + error.message + ". Please ensure location services are enabled.");
        }, {
            enableHighAccuracy: true, // Request for high accuracy location
            timeout: 15000, // Wait 15 seconds before timeout
            maximumAge: 0 // Force fresh location
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}


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

            console.log("Current Location:", currentLocation); // Debugging

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

                // Debugging: Check instructions
                console.log("Routing Instructions:", instructions); // Debugging

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
        }, function(error) {
            console.error("Geolocation error:", error); // Log any errors
            alert("Unable to retrieve your location. Please ensure location services are enabled.");
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}
navigator.geolocation.getCurrentPosition(function(position) {
    // Success handling
}, function(error) {
    console.error("Geolocation error:", error); // Log detailed error
    alert("Unable to retrieve your location. Error: " + error.message);
});
    </script>
</body>

</html>

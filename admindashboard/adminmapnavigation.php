<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plot Availability Map with Voice Navigation</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

    <style>
        #map {
            height: 600px;
            width: 100%;
        }

        #search-box {
            margin: 20px;
            text-align: center;
            width: 100%;
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

        .map-container1 {
            display: flex;
            margin-top: 10px;
            gap: 10px;
            width: 100%;
        }

        .map-container2 {
            display: flex;
            gap: 15px;
            margin-left: 20%;
        }

        .available {
            background-color: blue;
            width: 100px;
            height: 15px;
        }

        .notavailable {
            background-color: red;
            width: 100px;
            height: 15px;
        }

        .button-back {
            margin-right: 200px;
            padding-bottom:50px;
            padding-left: 20px;
        }
        #search-box{
            background-color:#18392b;
            color: white;
            border-radius: 10px;
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.2);
            width:98%;
        }
      
    </style>
</head>

<body>
    <div id="search-box">
        <br>
        <h2>Plot Availability Map with Voice Navigation</h2>
        <input type="text" id="block-number" placeholder="Enter Block (1-4)">
        <input type="number" id="plot-number" placeholder="Enter Plot Number (1-50)">
        <button onclick="locatePlot()">Locate Plot</button>
        <div class="map-container1">
            <div class="button-back">
                <a href="adminDashboard.php"><button><i class="fas fa-arrow-left"></i> Back to Main</button></a>
            </div>
            <div class="map-container2">
                Available: <div class="available"></div>
                Not Available: <div class="notavailable"></div>
            </div>
        </div>
    </div>

    <div id="map"></div>

    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $host = 'localhost'; 
    $db = 'capstone2db'; 
    $user = 'root'; 
    $pass = '';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT plot_id, block, plot_number, is_available FROM plots");
        $stmt->execute();
        $plots = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        $plots = [];
    }
    ?>

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

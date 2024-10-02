<?php
// Database connection
$servername = "localhost";  // Replace with your server name
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$dbname = "capstone2db";  // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if block is set
if (isset($_GET['block'])) {
    $block = intval($_GET['block']);  // Get block number from the request

    // Prepare an SQL query to get available plots for the selected block
    // Only fetch plots where `is_available = 1` (available for reservation)
    $sql = "SELECT plot_number FROM plots WHERE block = ? AND is_available = 1 ORDER BY plot_number ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $block);
    $stmt->execute();
    $result = $stmt->get_result();

    $availablePlots = [];
    while ($row = $result->fetch_assoc()) {
        $availablePlots[] = $row['plot_number'];  // Add each available plot to the array
    }

    // Return the available plots as JSON
    echo json_encode($availablePlots);

    $stmt->close();
}




$conn->close();
?>

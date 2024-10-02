<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../connection/connection.php';

try {
    $dsn = 'mysql:host=localhost;dbname=capstone2db';
    $username = 'root';
    $password = '';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);

    if (isset($_GET['block'])) {
        $block = $_GET['block'];
        // Debug: Check if block is received correctly
        error_log("Block: " . $block);

        $sql = "SELECT plot_number FROM plots WHERE block = :block AND is_available = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':block', $block);
        $stmt->execute();
        $availablePlots = $stmt->fetchAll();

        // Debug: Log the fetched plots
        error_log("Available Plots: " . json_encode($availablePlots));

        echo json_encode($availablePlots); // Send the results back as JSON
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

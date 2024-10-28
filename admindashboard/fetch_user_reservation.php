<?php
session_start();
require_once '../connection/connection.php'; // Include your database connection

if (isset($_GET['id'])) {
    $reservationId = $_GET['id'];

    try {
        // Set the PDO error mode to exception
        if ($conn) {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare the select statement to fetch only approved reservations
            $sql = "SELECT * FROM reservation WHERE id = :id AND status = 'approved'";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $reservationId, PDO::PARAM_INT);
            $stmt->execute();

            // Fetch the reservation data
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($reservation) {
                echo json_encode($reservation);
            } else {
                echo json_encode(["error" => "Approved reservation not found"]);
            }
        } else {
            echo json_encode(["error" => "Database connection failed"]);
        }

    } catch (PDOException $e) {
        echo json_encode(["error" => "Error: " . $e->getMessage()]);
    }

    // Close the connection
    $conn = null;
} else {
    echo json_encode(["error" => "No reservation ID provided"]);
}
?>

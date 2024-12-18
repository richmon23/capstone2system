<?php
session_start();
require_once '../connection/connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['reservation_id'])) {
    $reservationId = $_POST['reservation_id'];
    
    // Fetch reservation details
    $stmt = $pdo->prepare("SELECT * FROM reservation WHERE id = :id");
    $stmt->bindParam(':id', $reservationId);
    $stmt->execute();
    $reservation = $stmt->fetch();

    if ($reservation) {
        // Redirect to a payment gateway or show payment options
        header("Location: payment_options.php?reservation_id=" . $reservationId);
        exit();
    } else {
        echo "Reservation not found.";
    }
}
?>

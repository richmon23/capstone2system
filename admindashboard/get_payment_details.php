<?php
include 'db_connection.php'; // Include your database connection

$reservation_id = $_GET['reservation_id'];

$query = "SELECT total_amount, installment_amount, duration FROM payment WHERE reservation_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode($data);
?>

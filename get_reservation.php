<?php
require 'connection.php';

$id = $_GET['id'];
$sql = "SELECT * FROM reservations WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($reservation);

<?php
// Initialize payments array
$payments = [];

// Database connection
$conn = new mysqli("localhost", "root", "", "capstone2db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle AJAX request
if (isset($_POST['action']) && $_POST['action'] === 'fetch_payments') {
    $sql = "SELECT * FROM payment";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $payments[] = $row;
        }
    }
    echo json_encode($payments); // Return data in JSON format
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Records</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        table {
      

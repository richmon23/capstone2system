<?php
// Include your database connection using PDO
require_once '../connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Update the status in the database
    $sql = "UPDATE reservation SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect to a success page or back to the dashboard
        header("Location: admindashboard.php?message=Status updated successfully");
        exit;
    } else {
        echo "Error updating status.";
    }
} else {
    echo "Invalid request.";
}
?>

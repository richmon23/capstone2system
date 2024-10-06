<?php
session_start();
require_once '../connection/connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $userId = $_POST['id'];

    try {
        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the delete statement
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Bind the user ID to the query
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            echo "User deleted successfully!";
        } else {
            echo "Failed to delete the user.";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the connection
    $conn = null;
} else {
    echo "Invalid request or user ID not provided.";
}
?>

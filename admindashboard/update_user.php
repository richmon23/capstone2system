<?php
session_start();

// Include the database connection file
require_once '../connection/connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Safely retrieve the data from the form (use filters for security)
    $userId = isset($_POST['userId']) ? intval($_POST['userId']) : 0;
    $firstname = isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : '';
    $surname = isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : '';
    $contact = isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '';

    // Ensure that the user ID is valid
    if ($userId > 0) {
        try {
            // Prepare the SQL statement to update the user
            $sql = "UPDATE users SET firstname = :firstname, surname = :surname, contact = :contact, email = :email, address = :address WHERE id = :id";
            $stmt = $conn->prepare($sql);
            
            // Bind the parameters
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':surname', $surname);
            $stmt->bindParam(':contact', $contact);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

            // Execute the query
            if ($stmt->execute()) {
                // Send a success response back to the AJAX call
                echo json_encode(['success' => true]);
            } else {
                // Send an error response
                echo json_encode(['success' => false, 'message' => 'Failed to update user.']);
            }
        } catch (PDOException $e) {
            // Handle the error
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    } else {
        // Send an error response if the user ID is invalid
        echo json_encode(['success' => false, 'message' => 'Invalid user ID.']);
    }
}
?>

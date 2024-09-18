<?php
session_start();
require_once '../connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $review = $_POST['review'];
    $rating = $_POST['rating'];
    $userId = $_SESSION['user_id'];

    // Fetch the user's full name from the database
    try {
        $sql = "SELECT fullname FROM users WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $fullname = $user ? $user['fullname'] : null; 

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit; // Stop execution if there's a database error
    }

    if (empty($review) || empty($rating) || empty($fullname)) {
        echo "Please fill in all fields and make sure you are logged in.";
        exit;
    }

    try {
        $sql = "INSERT INTO reviews (user_id, fullname, userfeedback, rating, time) 
                VALUES (:user_id, :fullname, :userfeedback, :rating, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':fullname', $fullname, PDO::PARAM_STR);
        $stmt->bindParam(':userfeedback', $review, PDO::PARAM_STR);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Review saved successfully.";
            header("Location: customerreviews.php");
            exit;
        } else {
            echo "Failed to save the review. Please try again.";
        }

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}

$pdo = null;
?>
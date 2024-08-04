<?php
session_start();
require_once 'connection.php';

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];

// Prepare and execute
$stmt = $conn->prepare("SELECT id, firstname, surname, password FROM Users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $firstname, $surname, $hashed_password);
$stmt->fetch();

if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
    // Set session variables
    $_SESSION['user_id'] = $id;
    $_SESSION['firstname'] = $firstname;
    $_SESSION['surname'] = $surname;
    $_SESSION['email'] = $email;

    header("Location: welcome.php");
    exit();
} else {
    echo "Invalid email or password.";
}

// Close connections
$stmt->close();
$conn->close();
?>

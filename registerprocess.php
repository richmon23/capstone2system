<?php
session_start()

require_once 'connection.php'; // Include your database connection file;



// Retrieve form data
$firstname = $_POST['firstname'];
$surname = $_POST['surname'];
$email = $_POST['email'];
$password = $_POST['password'];
$birthdate = $_POST['birthdate'];
$gender = $_POST['gender'];

// Validate the input data (this is a basic example; consider more robust validation)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format");
}
if (empty($firstname) || empty($surname) || empty($password) || empty($birthdate) || empty($gender)) {
    die("All fields are required.");
}

// Hash the password for security
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Prepare and bind the SQL statement
$stmt = $conn->prepare("INSERT INTO Users (firstname, surname, email, password, birthdate, gender) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $firstname, $surname, $email, $hashed_password, $birthdate, $gender);

// Execute the statement and check for errors
if ($stmt->execute()) {
    $_SESSION['message'] = "Registration successful! Please log in.";
    header("Location: userlogin.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and the connection
$stmt->close();
$conn->close();
?>

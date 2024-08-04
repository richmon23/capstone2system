<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

// Safely retrieve and display user data
$firstname = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest';
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';

// For debugging purposes, you can uncomment the lines below to see session variables
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body>
    <h2>Welcome, <?php echo $firstname; ?>!</h2>
    <?php if ($email): ?>
        <p>You are logged in as <?php echo $email; ?>.</p>
    <?php else: ?>
        <p>Email address not available.</p>
    <?php endif; ?>
    <a href="logout.php">Logout</a>
</body>
</html>

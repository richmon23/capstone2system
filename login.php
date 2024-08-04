<?php
session_start();
require_once 'connection.php'; // Include your database connection file

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the email and password from the POST request
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare and execute the SQL statement using PDO
    $stmt = $conn->prepare("SELECT id, firstname, email, password FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    // Fetch the user data
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debugging output
    // var_dump($user); // Check the fetched user data
    // echo 'Password entered: ' . $password . '<br>';
    // echo 'Hashed password in DB: ' . $user['password'] . '<br>';
    // echo 'Password verification result: ' . (password_verify($password, $user['password']) ? 'true' : 'false') . '<br>';

    // Check if the user exists and verify the password
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['email'] = $user['email'];

        // Redirect to a protected page, e.g., welcome.php
        header("Location:admindashboard.php");
        exit();
    } else {
        // Invalid credentials, show error message
        // echo "<script>alert('Invalid email or password. Please try again.');</script>";
        // echo  <script> window.location = 'userlogin.php'</script>;
        
        echo "
				<script>alert('Invalid email or password. Please try again')</script>
				<script>window.location = 'userlogin.php'</script>
			";
    }
}

// Close the database connection
$conn = null;
?>

<?php
session_start();
require 'connection.php';


// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstname = $_POST['firstname'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $birthdate = $_POST['birthdate'] ?? '';
    $gender = $_POST['gender'] ?? '';

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (firstname, surname, email, password, birthdate, gender) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bindParam(1, $firstname);
    $stmt->bindParam(2, $surname);
    $stmt->bindParam(3, $email);
    $stmt->bindParam(4, $password);
    $stmt->bindParam(5, $birthdate);
    $stmt->bindParam(6, $gender);

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['message'] = "Registration successful! Please log in.";
        header("Location: userlogin.php");
        exit();
    } else {
        echo "Error: " . $errorInfo[2];
    }

    // Close connections
    $stmt = null;
    $conn = null;
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>
    <link rel="stylesheet" href="CSS_FILE/signupform.css">
</head>
<body>
    <main>
        <div class="row">
             <div class="col-9 container">
                <form method="POST" action="register.php">
                    <h2>Sign Up</h2>
                    <div class="div1 col-6">
                    <!-- <label for="firstname">First Name:</label> -->
                    <input type="text" id="firstname" name="firstname" maxlength="50" placeholder="Firstname" required>
                    <br>
                    <br>
                    <!-- <label for="surname">Surname:</label> -->
                    <input type="text"id="surname" name="surname" maxlength="50"  placeholder="Surname"required>
                    <br>
                    <br>
                    <input type="password" id="password" name="password" placeholder="New Password">
                    <br>
                    <br>
                    <input type="text" id="email" name="email" maxlength="50"  placeholder="Email"required>
                    <br>
                    <br>
                    <input type="date" id="birthdate" name="birthdate" placeholder="Birthdate"><br>
                    <br>
                    <label>Gender:</label><br>
                    <input type="radio" id="male" name="gender" value="Male" required>
                    <label for="male">Male</label><br>
                    <input type="radio" id="female" name="gender" value="Female">
                    <label for="female">Female</label><br>
                    <br>
                   <p>
                    By clicking Sign Up, <br> you agree to our Terms,Privacy Policy and Cookies Policy.You may receive SMS Notifications from us and can opt out anytime.</p>
                    <center><button type= "submit" class="button-29">Sign Up</button> </center> 
                    <br>
                    <span class="account">have an account?<a href="userlogin.php" class="signuplogin" > login</a>
                    <!-- <center>Don't have an account?<a href="register.php" class="loginbtnsignup"> Sign Up</a></center> -->
                   </span>
                </form>
                <br>
            </div>      
        </div>
    </main>
</body>
</html>

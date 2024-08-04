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
        echo "Error: " . $stmt->error;
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
    <link rel="stylesheet" href="./CSS_FILE/signup.css">
</head>
<body>
    <main>
     <h2>Sign Up</h2>
     <br>
     <br>
        <div class="row">
             <div class="col-9 container">
                <form method="POST" action="register.php">

                    <div class="div1 col-6">
                    <!-- <label for="firstname">First Name:</label> -->
                    <input type="text" id="firstname" name="firstname" maxlength="50" placeholder="firstname" required>
                    <br>
                    <!-- <label for="surname">Surname:</label> -->
                    <input type="text"id="surname" name="surname" maxlength="50"  placeholder="surname"required>
                    </div>
                    <br>
                    <div class="div2 col-6">
                    <!-- <label for="email">Email or Contact Number:</label> -->
                    <input type="text" id="email" name="email" maxlength="50"  placeholder="email"required>
                    </div>
                    <br>
                    <div class="div2 col-6">
                    <!-- <label for="password">New Password:</label> -->
                    <input type="password" id="password" name="password" placeholder="New Password"><br>
                    </div>
                    <div class="div2 col-6">
                    <!-- <label for="birthdate">Birthdate:</label> -->
                    <input type="date" id="birthdate" name="birthdate" placeholder="Birthdate"><br>
                    </div>
                    <br>
                    <br>
                    <label>Gender:</label><br>
                    <br>
                    <input type="radio" id="male" name="gender" value="Male" required>
                    <label for="male">Male</label><br>
                    <input type="radio" id="female" name="gender" value="Female">
                    <label for="female">Female</label><br>
                    <input type="radio" id="other" name="gender" value="Other">
                    <label for="other">Other</label><br><br>

                    <!-- Add a submit button -->
                   <center><button type= "submit">Sign Up</button> </center> 
                   <br>
                    <div class="agreement col-6">
                    <span> <a href="userlogin.php">have an account? login</a>
                   <br>
                    By clicking Sign Up, <br> you agree to our Terms,Privacy Policy and Cookies Policy.
                    <br> You may receive SMS Notifications from us and can opt out anytime.
                   </span>
                    </div>
                </form>
                <br>
            </div>      
        </div>
    </main>
</body>
</html>

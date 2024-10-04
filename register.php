<?php
session_start();
require_once './connection/connection.php';
    

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstname = $_POST['firstname'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address']?? '';
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $birthdate = $_POST['birthdate'] ?? '';
    $gender = $_POST['gender'] ?? '';

  

    // Execute the statement
   
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $emailExists = $stmt->fetchColumn();

    if ($emailExists) {
        // Use JavaScript alert to inform the user that the email is already registered
        echo "<br><br><script>alert('This email is already registered.');</script>";
    }else {
        $stmt = $conn->prepare("INSERT INTO users (firstname, surname, email,address, password, birthdate, gender) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $firstname);
        $stmt->bindParam(2, $surname);
        $stmt->bindParam(3, $email);
        $stmt->bindParam(4, $address);
        $stmt->bindParam(5, $password);
        $stmt->bindParam(6, $birthdate);
        $stmt->bindParam(7, $gender);
        
        if ($stmt->execute()) {
            echo "<br><br><script>
                alert('Registration successful!');
                window.location.href = 'userlogin.php';
            </script>";
        } else {
            echo "<br><br><script>alert('There was an error during registration. Please try again.');</script>";
        }
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
        <div class="row">
            <div class="col-6 left">
                <center><h1 class="header"> TIMELESSCARE </h1>
                    <br><h2 class="header2">BOGO MEMORIAL PARK</h2> 
                </center>
            </div>
            <div class="col-6  right">
                <form method="POST" action="register.php">
                    <h2>Sign Up</h2>
                    <div class="div1 col-6">
                    <!-- <label for="firstname">First Name:</label> -->
                    <div class="form-group">
                        <input type="text" id="firstname" name="firstname" maxlength="50" placeholder="" required>
                        <label for="firstname">Firstname</label>
                    </div>
                    <!-- <input type="text" id="firstname" name="firstname" maxlength="50" placeholder="Firstname" required>
                    <br>
                    <br> -->
                    <div class="form-group">
                    <input type="text"id="surname" name="surname" maxlength="50"  placeholder=""required>
                        <label for="Surname">Surname</label>
                    </div>
                    <!-- <label for="surname">Surname:</label> -->
                    <!-- <input type="text"id="surname" name="surname" maxlength="50"  placeholder="Surname"required>
                    <br>
                    <br> -->
                    <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="" required>
                        <label for="Password">Password</label>
                    </div>
                    <!-- <input type="password" id="password" name="password" placeholder="New Password">
                    <br>
                    <br> -->
                    <div class="form-group">
                        <input type="email" id="email" name="email" maxlength="50"  placeholder=""required>
                        <label for="Email">Email</label>
                    </div>
                    <!-- <input type="text" id="email" name="email" maxlength="50"  placeholder="Email"required>
                    <br>
                    <br> -->
                    <div class="form-group">
                    <input type="text"id="address" name="address" maxlength="50"  placeholder=""required>
                        <label for="Address">Address</label>
                    </div>
                    <div class="form-group">
                        <input type="Date"id="birthdate" name="birthdate" placeholder=""><br>
                        <!-- <label for="Date">Birthdate</label> -->
                    </div>
                    <!-- <input type="date" id="birthdate" name="birthdate" placeholder="Birthdate"><br>
                    <br> -->
                    <label>Gender:</label><br>
                    <input type="radio" id="male" name="gender" value="Male" required>
                    <label for="male">Male</label><br>
                    <input type="radio" id="female" name="gender" value="Female">
                    <label for="female">Female</label><br>
                    <br>
                   <p>
                    By clicking Sign Up, <br> you agree to our Terms,Privacy Policy and Cookies Policy.</p>
                    <center><button type= "submit" class="button-29">Sign Up</button> </center> 
                    <span class="account">have an account?<a href="userlogin.php" class="signuplogin" > login</a>
                    <!-- <center>Don't have an account?<a href="register.php" class="loginbtnsignup"> Sign Up</a></center> -->

                    <!-- You may receive SMS Notifications from us and can opt out anytime. -->
                   </span>
                </form>
                <br>
            </div>      
        </div>
</body>
</html>

<?php
session_start();
require_once './connection/connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstname = $_POST['firstname'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $email = $_POST['email'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $address = $_POST['address'] ?? '';
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $gender = $_POST['gender'] ?? '';
    $profile_pic = !empty($_FILES['profile_pic']['name']) ? $_FILES['profile_pic']['name'] : null;
    
    



     // Handle file upload
     $profilePic = $_FILES['profile_pic'];
     $uploadDir = "uploads/profile_pics/";
     $targetFile = $uploadDir . basename($profilePic['name']);
     $uploadOk = 1;
     $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
 
     // Check if the file is an image
     $check = getimagesize($profilePic['tmp_name']);
     if ($check === false) {
         echo "File is not an image.";
         $uploadOk = 0;
     }
 
     // Check file size (limit to 2MB)
     if ($profilePic['size'] > 2000000) {
         echo "Sorry, your file is too large.";
         $uploadOk = 0;
     }
 
     // Allow only certain formats
     if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
         echo "Only JPG, JPEG, PNG & GIF files are allowed.";
         $uploadOk = 0;
     }

     // If everything is ok, try to upload the file
     if ($uploadOk === 1) {
        if (move_uploaded_file($profilePic['tmp_name'], $targetFile)) {
            // Set profile_pic to the uploaded filename for database insertion
            $profile_pic = basename($profilePic['name']);
        } else {
            echo "Sorry, there was an error uploading your file.";
            $uploadOk = 0; // Set to 0 if file upload fails
        }
    }


    // Check if the email is already registered
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $emailExists = $stmt->fetchColumn();

    if ($emailExists) {
        echo "<br><br><script>alert('This email is already registered.');</script>";
    } else {
        // Insert new user into the users table
       // Prepare your SQL query using named parameters
$stmt = $conn->prepare("INSERT INTO users (firstname, surname, email, contact, address, password, gender, profile_pic) 
VALUES (:firstname, :surname, :email, :contact, :address, :password, :gender, :profile_pic)");

// Bind parameters using named placeholders
$stmt->bindParam(':firstname', $firstname);
$stmt->bindParam(':surname', $surname);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':contact', $contact);
$stmt->bindParam(':address', $address);
$stmt->bindParam(':password', $password);
$stmt->bindParam(':gender', $gender);
$stmt->bindParam(':profile_pic', $profile_pic); // Use named binding for profile_pic

// Execute the statement
if ($stmt->execute()) {
echo "<br><br><script>
alert('Registration successful!');
window.location.href = 'userlogin.php';
</script>";
} else {
echo "<br><br><script>alert('There was an error during registration. Please try again.');</script>";
}

    }
}








?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="CSS_FILE/signupform.css">
</head>
<body>
    <div class="row">
        <div class="col-6 left">
            <center>
                <h1 class="header"> TIMELESSCARE </h1>
                <br>
                <h2 class="header2">BOGO MEMORIAL PARK</h2> 
            </center>
        </div>
        <div class="col-6 right">
            <form method="POST" action="register.php" enctype="multipart/form-data">
                <h2>Sign Up</h2>
                <div class="form-group">
                    <input type="text" id="firstname" name="firstname" maxlength="50" placeholder="" required>
                    <label for="firstname">Firstname</label>
                </div>
                <div class="form-group">
                    <input type="text" id="surname" name="surname" maxlength="50" placeholder="" required>
                    <label for="surname">Surname</label>
                </div>
                <div class="form-group">
                    <input type="email" id="email" name="email" maxlength="50" placeholder="" required>
                    <label for="email">Email</label>
                </div>
                <div class="form-group">
                    <input type="password" id="password" name="password" placeholder="" required>
                    <label for="password">Password</label>
                </div>
                <div class="form-group">
                    <input type="text" id="contact" name="contact" maxlength="50" placeholder="" required>
                    <label for="contact">Contact</label>
                </div>
                <div class="form-group">
                    <input type="text" id="address" name="address" maxlength="50" placeholder="" required>
                    <label for="address">Address</label>
                </div>
                <!-- <div class="form-group">
                    <input type="date" id="birthdate" name="birthdate" placeholder="" required>
                    <label for="birthdate">Birthdate</label>
                </div> -->
                <label>Gender:</label><br>
                <input type="radio" id="male" name="gender" value="Male" required>
                <label for="male" class="gender-label">Male</label><br>
                <input type="radio" id="female" name="gender" value="Female">
                <label for="female" class="gender-label">Female</label><br><br>
                
                <div class="form-group">
                    <label for="profile_pic">Profile Picture:</label><br>
                    <input type="file" id="profile_pic" name="profile_pic" accept="image/*" required>
                </div>
                

                <p>By clicking Sign Up, you agree to our Terms, Privacy Policy, and Cookies Policy.</p>
                <center>
                    <button type="submit" class="button-29">Sign Up</button>
                </center>
                <span class="account">Have an account? <a href="userlogin.php" class="signuplogin">Login</a></span>
            </form>
            <br>
        </div>      
    </div>
</body>
</html>


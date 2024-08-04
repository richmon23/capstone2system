<?php
session_start();
?>

<?php
    if (isset($_SESSION['message'])) {
        echo "<p style='color: green;'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']); // Clear the message after displaying it
    }
    ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./CSS_FILE/login.css">
</head>


<body>
    <main>
        <div class="row">
            <div class="col-6 left">
                <center><h1 class="header"> TIMELESSCARE </h1>
                    <br><h2 class="header2">BOGO MEMORIAL PARK</h2> 
                </center>
            </div>
            <div class="col-6 right">
                <center>
                <form action="login.php" method="post" class="form">
                    <center>
                    <img src="./images/bogomemoriallogo.png" alt="">
                    <h2>WELCOME ADMIN</h2>
                    <br>
                    <label for="Username">Email</label>
                    <br>
                    <input type="text" name="email" id="email"  placeholder="Enter Email" required>
                    <br>
                    <br>
                    <label for="Password">Password</label>
                    <br>
                    <input type="password" name="password" id="password"  placeholder="Enter Password" required>
                    <br>
                    <br>
                    <button type="submit">Login</button>
                    </center>  
                </form>
                </center>
                <br>
                <center><a href="register.php">Don't have an account? Sign Up</a></center>
            </div>
        </div>
    </main>   
</body>
</html>
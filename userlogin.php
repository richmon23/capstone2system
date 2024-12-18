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
    <link rel="stylesheet" href="./CSS_FILE/userlogin.css">

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
                        <div class="form-group">
                            <input type="email" name="email" id="email" placeholder=" " required>
                            <label for="email">Email</label>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" id="password" placeholder=" " required>
                            <label for="password">Password</label>
                            <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">
                                <svg id="eye-icon" viewBox="0 0 24 24">
                                    <path d="M12 5c-7.732 0-12 7-12 7s4.268 7 12 7 12-7 12-7-4.268-7-12-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
                                </svg>
                            </button>
                        </div>
                        <br>
                        <button type="submit" class="login" role="button">Login</button>
                        </center>  
                        <br>
                        <center>Don't have an account?<a href="register.php" class="loginbtnsignup"> Sign Up</a></center>
                    </form>
                </center>
                <br>
            </div>
        </div>
    </main>   
    <script src="./JS_FILE/loginshowpassword.js"></script>
</body>
</html>





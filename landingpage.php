<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link rel="stylesheet" href="./CSS_FILE/landingpage.css">
</head>
<body>
<header class="header">
        <img src="./images/bogomemoriallogo.png" alt="Memorial Logo" class="logo">
        <!-- <button class="hamburger" onclick="toggleMenu()">☰</button> -->
        <nav class="nav" id="navMenu">
            <a href="#">Home</a>
            <a href="#">Contact Us</a>
            <a href="#">Packages</a>
            <a href="#">Photos</a>
            <a href="#">Maps</a>
            <a href="register.php"><button class="btnsignup">Sign Up</button></a>
        </nav>
    </header>
    <main>
        <div class="row">
            <div class="col-3 left-content">
                <h1 class="timeless">TIMELESS CARE</h1>
                <h1 class="memorial">BOGO MEMORIAL PARK</h1>
                <br>
               <a href="userlogin.php"> <button type="submit" class="login">Login</button></a>
            </div>
            <div class="col-6 right-content">
                <img src="./images/memorialparkpic.jpg" alt="bogomemorialpark">
            </div>
        </div>
        <br>
        <br>
        <br>
        <center><h1>PACKAGES OFFERED</h1></center>
        <section class="packages">
            <div class="package-detail"><h1>Lawn</h1>
                <br>
                <img src="./images/lawn.jpg" alt="">
            </div>
            <div class="package-detail"><h1>Garden</h1>
                <br>
                <img src="./images/pic21.jpg" alt="">
            </div>
            <div class="package-detail"><h1>Family State</h1>
                <br>
                <img src="./images/family.jpg" alt="">
            </div>
        </section>
        <center><h1 class="memorialmaptext">Bogo Memorial Park Map</h1></center>
        <section class="map-section">
            <div class="bogomap">
                <iframe 
                    style="width: 100%; height: 100%; border: 0;" 
                    src="https://www.google.com/maps/embed/v1/place?q=bogo+memorial+park&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8"
                    allowfullscreen>
                </iframe>
            </div>
        </section>
    </main>

    <footer class="footer">
        <h1>Footer</h1>
    </footer>
    <!-- <script>
        function toggleMenu() {
            var navMenu = document.getElementById('navMenu');
            if (navMenu.style.display === 'block') {
                navMenu.style.display = 'none';
            } else {
                navMenu.style.display = 'block';
            }
        }
    </script> -->
</body>
</html>

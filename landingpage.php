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
            <a href="#contact">Contact Us</a>
            <a href="#">Packages</a>
            <a href="#">Photos</a>
            <a href="#map">Maps</a>
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
        <br>  
        <br>
        <br>
        <br>
        <br> 
        <br>
        <br>
        <br>      
        <center><h1>PACKAGES OFFERED</h1></center>
        <br>
        <br>
        <section class="packages">
            <div class="package-detail"><h1>Lawn</h1>
                <br>
                <img src="./images/lawnpackage.jpg" alt="">
            </div>
            <div class="package-detail"><h1>Garden</h1>
                <br>
                <img src="./images/gardenpackage.jpg" alt="">
            </div>
            <div class="package-detail"><h1>Family State</h1>
                <br>
                <img src="./images/familypackage.jpg" alt="">
            </div>
        </section>
        <br>
        <br>
        <br>
        <br>
        <center><h1 class="memorialmaptext" id="map">Bogo Memorial Park Map</h1></center>
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
    <br>
    <br>
    <br>
    <br>
    <br>
    <footer class="footer" id="contact">
        <div class="divimg">
            <img src="./images/bogomemoriallogo.png" alt="bogomemoriallogo">
           <br>
           <!-- <p class="footerqoute">The First and only Memorial Park  in
Bogo City,Cebu where Memories Live 
Forever...</p> -->
        </div>
        <div class="div1">
        <h1>Packages</h1>
        <p>Lawn</p>
        <p>Garden</p>
        <p>Family State</p>
        </div>
        <div class="div2">
            <h1>Contact Us</h1>
            <p>Phone:(123) 456-7890</p>
            <p>Email:info@bogomemorial.com</p>
            <p>Address:Taytayan Hills, Bogo City, Cebu City, Philippines</p>
        </div>
        <div class="div3">
            <h1>Follow Us</h1>
            <div class="social-media">
                <a href="https://www.facebook.com/BogoMemorialPark"><img src="./images/facebook.png" alt="Facebook"></a>
                <a href="#"><img src="./images/twitter.png" alt="Twitter"></a>
                <a href="#"><img src="./images/instagram.png" alt="Instagram"></a>
            </div>
        </div>
        </div>
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

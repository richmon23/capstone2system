<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link rel="stylesheet" href="CSS_FILE/index.css">
    <style>
    

    </style>
</head>
<body>

    <!-- TODO: header section -->
    <header>
        <div class="menu-bar" id="menu-bar">
            <div class="logo"><img src="images/bogomemoriallogo.png" alt=""></div>
            <div class="menu-icon" onclick="toggleMenu()">☰</div>
            <nav>
                <ul id="nav-links">
                    <li><a href="#menu-bar" class="ctn">Home</a></li>
                    <li><a href="#map">Map</a></li>
                    <li><a href="#photos">Photos</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                    <li><a href="#packages">Packages</a></li>
                    <li> <a href="register.php">Sign Up</a></li>
                </ul>
            </nav>
        </div>
        <div class="header-content">
            <h2>A Memory Lives Forever</h2>
            <div class="line"></div>
            <h1>Bogo Memorial Park</h1>
            <br>
            <!-- <a href="#" class="btn">Login</a> -->
            <a href="userlogin.php" class="btn">Login</a>
        </div>
    </header>

    <!-- TODO:Main section -->

    <main>
        <div class="parallax1"></div>
        <div class="parallax1-detail">
        <h2>Lawn</h2> 
        <p>A simple yet elegant choice, ideal for honoring those who have passed with a peaceful resting place amidst nature.</p>
        </div>

        <div class="parallax2"></div>
        <div class="parallax2-detail">
        <h2>Garden</h2>
        <p>This option provides a picturesque setting, surrounded by vibrant flowers and greenery, creating a serene environment for remembrance.</p>
        </div>
            
        <div class="parallax3"></div>
        <div class="parallax3-detail">
        <h2>Family Estate </h3> 
        <p>Designed for families wishing to be together even in rest, this package offers a larger space for multiple interments, allowing for a lasting family legacy. 
        Bogo Memorial Park is not just a cemetery; it is a sanctuary where families can celebrate the lives of their loved ones in a dignified and peaceful environment. It stands as a testament to the enduring nature of love and remembrance.</p>
        </div>
 

        <!-- TODO: Packages section -->

        <!-- <center> <h1  class="packages-header">Packages</h1></center> -->
        <div class="packages" id="packages">
            <div class="box">
                <img src="images/lawnpackage.jpg" alt="lawn">
                <h2>Lawn</h2>
                <p>A simple yet elegant choice, ideal for honoring those who have passed with a peaceful resting place amidst nature.</p>
                <!-- <a href="#" class="Packages-btn">Book Now</a> -->
            </div>
            <div class="box">
                <img src="images/gardenpackage.jpg" alt="garden">
                <h2>Garden</h2>
                <p>This option provides a picturesque setting, surrounded by vibrant flowers and greenery, creating a serene environment for remembrance.</p>
                <!-- <a href="#" class="Packages-btn">Book Now</a> -->
            </div>
            <div class="box">
                <img src="images/familypackage.jpg" alt="family">
                <h2>Family</h2>
                <p> Designed for families wishing to be together even in rest, this package offers a larger space for multiple interments, allowing for a lasting family legacy.</p>
                <!-- <a href="#" class="Packages-btn">Book Now</a> -->
            </div>
            
        </div>


        <!-- TODO: Photos section  -->
        <div class="slideshow-container" id="photos">
            
            <div class="mySlides fade">
                <div class="numbertext">1 / 3</div>
                <img src="images/pic2.jpg" style="width:100%">
                <!-- <div class="text">Caption Text</div> -->
            </div>

            <div class="mySlides fade">
                <div class="numbertext">2 / 3</div>
                <img src="images/pic2.jpg" style="width:100%">
                <!-- <div class="text">Caption Two</div> -->
            </div>

            <div class="mySlides fade">
                <div class="numbertext">3 / 3</div>
                <img src="images/pic2.jpg" style="width:100%">
                <!-- <div class="text">Caption Three</div> -->
            </div>
        </div>
        <br>
        <div style="text-align:center">
            <span class="dot"></span> 
            <span class="dot"></span> 
            <span class="dot"></span> 
        </div>  

        <br>
        <br>
        <br>
          <!-- TODO:MAP -->
        <div class="map" id="map">
        <iframe 
                    style="width: 100%; height: 100%; border: 0;" 
                    src="https://www.google.com/maps/embed/v1/place?q=bogo+memorial+park&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8"
                    allowfullscreen>
                </iframe>
        </div>
    </main>
    <script defer src="https://app.fastbots.ai/embed.js" data-bot-id="cm0e07nix01t3n6bekk43upsx"></script>
    <footer class="footer" id="contact">
        <div class="divimg">
            <img src="images/bogomemoriallogo.png" alt="bogomemoriallogo">
           <br>
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
                <a href="https://www.facebook.com/BogoMemorialPark"><img src="images/facebook.png" alt="Facebook"></a>
                <a href="#"><img src="images/twitter.png" alt="Twitter"></a>
                <a href="#"><img src="images/instagram.png" alt="Instagram"></a>
            </div>
        </div>
        </div>
    </footer>

   
</body>
<script>
    function toggleMenu() {
        const navLinks = document.getElementById('nav-links');
        navLinks.classList.toggle('show');
    }
</script>
<script>
let slideIndex = 0;
showSlides();

function showSlides() {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}    
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
  setTimeout(showSlides,3000); // Change image every 2 seconds
}
</script>
</html>

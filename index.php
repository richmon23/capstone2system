<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimelessCare</title>
    <link rel="stylesheet" href="CSS_FILE/index.css">
    
    <!-- AOS Library CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>

    <!-- Header Section -->
    <header class="header" id="header">
        <div class="logo">
            <img src="images/bogomemoriallogo.png" alt="Logo" class="header-logo">
        </div>
        <nav class="nav">
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#packages">Packages</a></li>
                <li><a href="#gallery">Gallery</a></li>
                <li><a href="#map">Map</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
        <div class="login">  
        <a href="userlogin.php" class="login-logo">
        <img src="images/account.png" alt="Login Logo" title="login">
        </a>      
      
        </div>  
    </header>

    <!-- Main Content -->
    <main>
        <section id="home" class="hero" data-aos="fade-up">
            <h1>TimelessCare: A Memorial Park Management System</h1>
            <p>Welcome to Bogo Memorial Park, where we honor the memories of loved ones.</p>
    <a href="register.php" class="button-71" role="button" title="Signup">Sign Up</a>
        </section>

        <section id="packages" data-aos="fade-right">
            <h2>Our Packages</h2>
            <div class="package-container">
                <div class="package" data-aos="zoom-in">
                    <img src="images/lawnpackage.jpg" alt="Lawn Package" class="zoom">
                    <h3>Lawn</h3>
                    <p>A simple yet elegant choice.</p>
                </div>
                <div class="package" data-aos="zoom-in">
                    <img src="images/gardenpackage.jpg" alt="Garden Package" class="zoom">
                    <h3>Garden</h3>
                    <p>Surrounded by vibrant flowers and greenery.</p>
                </div>
                <div class="package" data-aos="zoom-in">
                    <img src="images/familypackage.jpg" alt="Family Package" class="zoom">
                    <h3>Family Estate</h3>
                    <p>A larger space for multiple interments.</p>
                </div>
            </div>
        </section>

        <section id="gallery" data-aos="fade-left">
            <h2>Our Gallery</h2>
            <div class="package-container">
                <div class="package" data-aos="zoom-in">
                    <img src="images/img1.jpg" alt="Gallery Image 1" class="zoom">
                    <h3>front</h3>
                </div>
                <div class="package" data-aos="zoom-in">
                    <img src="images/img2.jpg" alt="Gallery Image 2" class="zoom">
                    <h3>back</h3>
                </div>
                <div class="package" data-aos="zoom-in">
                    <img src="images/img3.jpg" alt="Gallery Image 3" class="zoom">
                    <h3>hallways</h3>
                </div>
            </div>
        </section>

        <section id="about" data-aos="fade-right">
            <h2>About Us</h2>
            <p><I>"Bogo Memorial Park serves as a serene sanctuary where families can honor and celebrate the lives of their loved ones in a dignified, tranquil, and beautifully maintained environment, offering comfort and solace during times of remembrance."
            <I></p>
        </section>

        <section id="reviews" data-aos="fade-up">
    <h2>Customer Reviews</h2>
    <div class="review-container">
        <div class="review-card" data-aos="zoom-in">
            <blockquote>"The park is so peaceful and beautifully maintained. It gave us comfort during our time of need."</blockquote>
            <p>- Maria Santos</p>
        </div>
        <div class="review-card" data-aos="zoom-in">
            <blockquote>"The staff was very accommodating, and the environment felt dignified and serene."</blockquote>
            <p>- John Delgado</p>
        </div>
        <div class="review-card" data-aos="zoom-in">
            <blockquote>"We are so grateful for the service provided. It truly honored the memory of our loved one."</blockquote>
            <p>- Sarah Lee</p>
        </div>
    </div>
</section>

        <section id="map" data-aos="fade-up">
            <h2>Find Us</h2>
            <iframe 
                src="https://www.google.com/maps/embed/v1/place?q=bogo+memorial+park&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8"
                style="border:0; width:100%; height:300px;" allowfullscreen>
            </iframe>
        </section>

        <section id="contact" data-aos="fade-up">
            <h2>Contact Us</h2>
            <p>Email: info@bogomemorial.com</p>
            <p>Phone: (123) 456-7890</p>
            <p>Address: Taytayan Hills, Bogo City, Cebu City, Philippines</p>
        </section>
    </main>

     <!-- MEMORIAL PARK CHATBOT -->

    <script defer src="https://app.fastbots.ai/embed.js" data-bot-id="cm1k0jcav3kh4r4bkexv1bzse"></script>
    
    <!-- Footer Section with Back to Top Button -->
    <footer>
        <p>&copy; 2024 Bogo Memorial Park</p>
        <div class="back-to-top" style="position: absolute; right: 20px; bottom: 20px;">
            <a href="#header" id="backToTop">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd" d="M11.47 10.72a.75.75 0 0 1 1.06 0l7.5 7.5a.75.75 0 1 1-1.06 1.06L12 12.31l-6.97 6.97a.75.75 0 0 1-1.06-1.06l7.5-7.5Z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M11.47 4.72a.75.75 0 0 1 1.06 0l7.5 7.5a.75.75 0 1 1-1.06 1.06L12 6.31l-6.97 6.97a.75.75 0 0 1-1.06-1.06l7.5-7.5Z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </footer>
    

    <!-- AOS Library JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init(); // Initialize AOS

        // Back to Top button functionality
        document.getElementById("backToTop").addEventListener("click", function(e) {
            e.preventDefault(); // Prevent default anchor behavior
            window.scrollTo({
                top: 0,
                behavior: "smooth" // Smooth scrolling effect
            });
        });
    </script>
</body>
</html>

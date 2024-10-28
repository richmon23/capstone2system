<?php
// Start the session and ensure the user is logged in
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

// Include the database connection
require_once '../connection/connection.php';



$firstname = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest';
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';


// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $review = isset($_POST['review']) ? $_POST['review'] : null;
    $rating = isset($_POST['rating']) ? $_POST['rating'] : null;
    $userId = $_SESSION['user_id'];

    // Fetch the user's first name from the database
    try {
        $sql = "SELECT firstname FROM users WHERE id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $firstname = $user ? $user['firstname'] : null;

    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit; // Stop execution if there's a database error
    }

    // Validate inputs
    if (empty($review) || empty($rating) || empty($firstname)) {
        echo "Please fill in all fields and make sure you are logged in.";
    } else {
        // Insert review into the database
        try {
            $sql = "INSERT INTO reviews (user_id, fullname, userfeedback, rating, time) 
                    VALUES (:user_id, :fullname, :userfeedback, :rating, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':fullname', $firstname, PDO::PARAM_STR);
            $stmt->bindParam(':userfeedback', $review, PDO::PARAM_STR);
            $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo "Review saved successfully.";
                header("Location: customerreviews.php");
                exit();
            } else {
                echo "Failed to save the review. Please try again.";
            }
        } catch (PDOException $e) {
            echo "Database error: " . $e->getMessage();
        }
    }
}

// Fetch user profile picture from the database
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT profile_pic FROM users WHERE id = :id");
$stmt->bindParam(':id', $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if user profile picture exists
$profilePic = !empty($user['profile_pic']) ? $user['profile_pic'] : 'default.png'; // Use a default image if none is found
   


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews</title>
    <link rel="stylesheet" href="./customerdashboard_css/customerreview.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

    <div class="row">
    <div class="left-content col-3">
    <div class="adminprofile">
                            <center>
                            <img src="../uploads/profile_pics/<?php echo $profilePic; ?>" alt="Profile Picture">
                                <div class="dropdown">
                                    <button class="dropdown-btn">
                                        <?php echo "<h4> $firstname</h4>" ?> 
                                    </button>
                                    <!-- <i class="fas fa-caret-down dropdown-icon"></i> -->
                                    <!-- <div class="dropdown-content">
                                        <button onclick="openModal('changePasswordModal')">Change Password</button>
                                        <button onclick="openModal('termsModal')">Terms and Conditions</button>
                                    </div> -->
                                </div>
                        </center>
                    </div>
                        <br>
                        <div class="adminlinks">
                            <span><img src="../images/dashboard.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerDashboard.php">Dashboard</a></span> 
                            <!-- <span><img src="../images/deceased.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerDeceased.php">Deceased</a></span> -->
                            <span><img src="../images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerreservation.php">Reservation</a></span>
                            <span><img src="../images/review.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerreviews.php">Reviews</a></span>
                            <span><img src="../images/plot.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerviewavailableplot.php">Available Plot & Block</a></span>
                            <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerpayment.php">Payments</a></span>
                            <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
                        </div>
                        <br>
                    </div>
                    <div class="main">
            <div class="right-content1">
                <br>
                <br>
                <span class="header">&nbsp;&nbsp;Write your Review </span>
                <br>
                <div class="right-header col-9">
                    <form id="myForm" action="" method="post">
                        <textarea rows="4" name="review" cols="50"></textarea>
                        <div class="rating">
                            <input type="radio" id="star5" name="rating" value="5">
                            <label for="star5">★</label>
                            <input type="radio" id="star4" name="rating" value="4">
                            <label for="star4">★</label>
                            <input type="radio" id="star3" name="rating" value="3">
                            <label for="star3">★</label>
                            <input type="radio" id="star2" name="rating" value="2">
                            <label for="star2">★</label>
                            <input type="radio" id="star1" name="rating" value="1">
                            <label for="star1">★</label>
                        </div>
                    </form>
                    <div class="form-buttons">
                        <button type="submit" class="submit" form="myForm">Submit</button>
                        <button type="button"class="cancel" onclick="window.location.href='customerreviews.php'">Cancel</button>
                    </div>
                </div> 
            </div>

            <!-- TODO: Reviews -->
            <div class="right-content-reviews">
                <div class="table-container">
                <div class="myTable">
    <?php
    $sql = "SELECT id, fullname, userfeedback, rating, time FROM reviews ORDER BY time DESC LIMIT 1000";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "
            <div class='card'>
                <div class='card-header'>
                    <h3>" . htmlspecialchars($row['fullname']) . "</h3>
                    <span class='time'>" . htmlspecialchars($row['time']) . "</span>
                </div>
                <div class='card-body'>
                    <p>" . htmlspecialchars($row['userfeedback']) . "</p>
                </div>
                <div class='card-footer'>
                    <div class='rating'>";
                    
            // Display stars based on the rating
            $rating = (int)$row['rating'];
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $rating) {
                    echo "<i class='fas fa-star'></i>"; // Filled star
                } else {
                    echo "<i class='far fa-star'></i>"; // Empty star
                }
            }

            echo "</div></div></div>";
        }
    } else {
        echo "<p>No reviews available.</p>";
    }
    ?>
</div>
    
                <?php $pdo = null; // Closing the PDO connection ?>         
            </div>   
        </div>
    </div> 
</body>
</html>

<?php
// Start the session and ensure the user is logged in
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

// Include the database connection
require_once '../connection/connection.php';

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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="./customerdashboard_css/customerreview.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

    <div class="row">
        <div class="left-content col-4">
            <div class="memoriallogo"><img src="../images/bogomemoriallogo.png" alt="bogomemoriallogo"></div>
            <div class="hamburgermenu"><img src="../images/hamburgermenu.png" alt="hamburgermenu"></div> 
            <div class="adminprofile">
                <center><img src="../images/female.png" alt="adminicon">
                </center>
            </div>
            <center>
            <br>
            <div class="adminlinks">
                <span><img src="../images/dashboard.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/customerdashboard.php">Dashboard</a></span> 
                <span><img src="../images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/customerreservation.php">Reservation</a></span>
                <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/customerpayment.php">Payments</a></span>
                <span><img src="../images/review.png" alt="">&nbsp;&nbsp;&nbsp;<a href="/customerdashboard/customerreviews.php">Reviews</a></span>
                <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
             </div>
            <br>
            </center>
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
                    <table id="myTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NAME</th>
                                <th>USER FEEDBACK</th>
                                <th>RATING</th>
                                <th>TIME</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql = "SELECT id, fullname, userfeedback, rating, time FROM reviews ORDER BY time DESC LIMIT 1000";  
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();

                                if ($stmt->rowCount() > 0) {
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>
                                                <td>" . htmlspecialchars($row["id"]). "</td>
                                                <td>" . htmlspecialchars($row["fullname"]). "</td>
                                                <td>" . htmlspecialchars($row["userfeedback"]). "</td>
                                                <td>";
                                                
                                        // Display stars based on the rating
                                        $rating = (int)$row['rating'];
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $rating) {
                                                echo "<i class='fas fa-star'></i>"; // Filled star
                                            } else {
                                                echo "<i class='far fa-star'></i>"; // Empty star
                                            }
                                        }

                                        echo "</td>
                                                <td>" . htmlspecialchars($row["time"]). "</td>
                                            </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>No reviews available</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>   
                </div>            
                <?php $pdo = null; // Closing the PDO connection ?>         
            </div>   
        </div>
    </div> 
</body>
</html>

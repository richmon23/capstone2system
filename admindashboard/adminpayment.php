<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

$firstname = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest';
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';

require_once '../connection/connection.php';

// Check if a search term is provided via GET
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare SQL query for approved reservations with a search filter on fullname
$sql = "SELECT firstname, package, plotnumber, blocknumber, status 
        FROM reservation 
        WHERE status = 'approved' 
        AND firstname LIKE :searchTerm";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if it's an AJAX request and return JSON response if true
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($result);
    exit();
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
    <title> Payment </title>
    <link rel="stylesheet" href="./admindashboardcss/adminpayment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
</head> 
<body>

    
    <!-- <a href="logout.php">Logout</a> -->

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
                            <span><img src="../images/dashboard.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminDashboard.php">Dashboard</a></span> 
                            <span><img src="../images/deceased.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminDeceased.php">Deceased</a></span>
                            <span><img src="../images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreservation.php">Reservation</a></span>
                            <span><img src="../images/review.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreviews.php">Reviews</a></span>
                            <span><img src="../images/users.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminusers.php">User's</a></span>
                            <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminpayment.php">Payments</a></span>
                            <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
                        </div>
                        <br>
                </div>
                <div class="main">
                <div class="right-content1">
                    <div class="right-header col-10">
                        <br>
                        <span><h2>PAYMENT</h2></span>
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <!-- <input type="text" class="search-input" placeholder="Search"> -->
                            <input type="text" id="search" class="search-input" placeholder="Search Customers by Name..." onkeyup="searchUsers()">
                        <!-- <button onclick="openAddModal()">Add Reservation</button> -->
                    </div>
                </div>
                <div class="right-content2">
                    <div class="right-header2 col-9">
                        <div class="left-content1">
                            <div class="table-container">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Package</th>
                                            <th>Plot Number</th>
                                            <th>Block Number</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="userTableBody">
                                        <?php
                                        // Render initial results for the page load
                                        if ($result) {
                                            foreach ($result as $row) {
                                                echo "<tr>
                                                        <td>" . htmlspecialchars($row['firstname']) . "</td>
                                                        <td>" . htmlspecialchars($row['package']) . "</td>
                                                        <td>" . htmlspecialchars($row['plotnumber']) . "</td>
                                                        <td>" . htmlspecialchars($row['blocknumber']) . "</td>
                                                        <td>" . htmlspecialchars($row['status']) . "</td>
                                                    </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'>No approved reservations found.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="right-content">
                            <div id="userDisplay">
                                <div id="userDetails">
                                    <img id="userImage" src="../images/female.png" alt="User Image">
                                    <br>
                                    <div class="user-row">
                                        <label>Name:</label>
                                        <span id="labelFirstname">Juan Tamad</span>
                                    </div>
                                    <div class="user-row">
                                        <label>Package:</label>
                                        <span id="labelPackage">Package</span>
                                    </div>
                                    <div class="user-row">
                                        <label>Plot:</label>
                                        <span id="labelPlot">0</span>
                                    </div>
                                    <div class="user-row">
                                    <label>Block:</label>
                                    <span id="labelBlock">0</span>
                                    </div>
                                    <button id="proceedPaymentButton" onclick="openPaymentModal()">Proceed to Payment</button>
                                    <!-- <button onclick="openUpdateModal()">Edit</button> -->
                                    <!-- <button class="btn-danger" onclick="deleteUser()">Delete</button> -->
                                </div>
                            </div>  
                        </div>
                    </div>
                    
                </div>
            
        </div>

<!-- Payment Modal -->
<div id="paymentModal" class="modal">
    <div class="modal-content">
        <!-- Close Button (X) -->
        <span class="close" onclick="closePaymentModal()">&times;</span>
        
        <h2>Proceed with Payment</h2>
        <form id="paymentForm">
            <div class="modal-body">
                <div class="form-group">
                    <label>Fullname:</label>
                    <input type="text" id="modalFullname" name="fullname" readonly>
                </div>
                <div class="form-group">
                    <label>Package:</label>
                    <input type="text" id="modalPackage" name="package" readonly>
                </div>
                <div class="form-group">
                    <label>Plot Number:</label>
                    <input type="text" id="modalPlot" name="plotnumber" readonly>
                </div>
                <div class="form-group">
                    <label>Block Number:</label>
                    <input type="text" id="modalBlock" name="blocknumber" readonly>
                </div>
                <div class="form-group">
                    <label>Payment Amount:</label>
                    <input type="text" id="paymentAmount" name="amount" placeholder="Enter amount">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit">Confirm Payment</button>
            </div>
        </form>
    </div>
</div>




        <script>function searchUsers() {
    // Get the search input value
    const searchQuery = document.getElementById('search').value;

    // Create an XMLHttpRequest object
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'search_approved_reservation.php?query=' + encodeURIComponent(searchQuery), true);

    // Define what happens on successful data submission
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Parse the JSON data returned from the server
            const users = JSON.parse(xhr.responseText);

            // Select the table body element where we will display users
            const userTableBody = document.getElementById('userTableBody');
            userTableBody.innerHTML = '';  // Clear the table body

            if (users.length > 0) {
                // Loop through each user and insert into the table
                users.forEach(function(user) {
                    const row = document.createElement('tr');

                    // Set the onClick to populate the details when a user is clicked
                    row.onclick = function() {
                        displayUserDetails(user.firstname, user.package, user.plotnumber, user.blocknumber);
                    };

                    row.innerHTML = `
                        <td>${user.firstname}</td>
                        <td>${user.package}</td>
                        <td>${user.plotnumber}</td>
                        <td>${user.blocknumber}</td>
                        <td>${user.status}</td>
                    `;

                    userTableBody.appendChild(row);  // Add the row to the table body
                });
            } else {
                // If no users found, display message
                userTableBody.innerHTML = '<tr><td colspan="5">No reservations found</td></tr>';
            }
        } else {
            console.error('Error fetching users:', xhr.status);
        }
    };

    // Send the request to the server
    xhr.send();
}

// Function to display the user's details
function displayUserDetails(firstname, packageName, plotNumber, blockNumber) {
    document.getElementById('labelFirstname').textContent = firstname;
    document.getElementById('labelPackage').textContent = packageName;
    document.getElementById('labelPlot').textContent = plotNumber;
    document.getElementById('labelBlock').textContent = blockNumber;
}

// Automatically load all approved reservations on page load
window.onload = function() {
    searchUsers(); // This will load all approved reservations initially
};


// Function to open the payment modal and fill in the details
function openPaymentModal() {
    const firstname = document.getElementById('labelFirstname').textContent;
    const packageName = document.getElementById('labelPackage').textContent;
    const plotNumber = document.getElementById('labelPlot').textContent;
    const blockNumber = document.getElementById('labelBlock').textContent;

    // Set the modal input values
    document.getElementById('modalfirstname').value = firstname;
    document.getElementById('modalsurname').value = surname;
    document.getElementById('modalPackage').value = packageName;
    document.getElementById('modalPlot').value = plotNumber;
    document.getElementById('modalBlock').value = blockNumber;

    // Show the modal and trigger fade-in animation
    document.getElementById('paymentModal').classList.add('show');
}

// Function to close the modal
function closePaymentModal() {
    document.getElementById('paymentModal').classList.remove('show');
}

</script>

</body>
</html>
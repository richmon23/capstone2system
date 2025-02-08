<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

// Safely retrieve and display user data
$firstname = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest';
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';

require_once '../connection/connection.php'; // Include your database connection file

try {
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL query to fetch user names
    $sql = "SELECT * FROM users"; // Use '*' to fetch all user columns
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetch all the results
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetching as an associative array

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
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
    <title>Users</title>
    <link rel="stylesheet" href="./admindashboardcss/adminusers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <style>
        .modal {
    display: none; /* Hide modal by default */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    padding: 20px;
    width: 50%;
    max-width: 400px;
    border-radius: 10px;
}

    </style>
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
                            <span><img src="../images/dashboard.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminDashboard.php">Dashboard</a></span> 
                            <span><img src="../images/deceased.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminDeceased.php">Deceased</a></span>
                            <span><img src="../images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreservation.php">Reservation</a></span>
                            <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminpayment.php">Transaction</a></span>
                            <span><img src="../images/users.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminusers.php">User's</a></span>
                            <span><img src="../images/review.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreviews.php">Reviews</a></span>
                            <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
                        </div>
                        <br>
                </div>
                <div class="main">
                <div class="right-content1">
                    <div class="right-header col-10">
                        <br>
                        <h2>USERS DATA</h2>
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <!-- <input type="text" class="search-input" placeholder="Search"> -->
                            <input type="text" id="search" class="search-input" placeholder="Search Customers ..." onkeyup="searchUsers()">
                        </div>
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
                                    <th>First Name</th>
                                    <th>Surname</th>
                                </tr>
                            </thead>
                                <tbody id="userTableBody">
                                <?php
                                if (count($users) > 0) {
                                    foreach ($users as $row) {
                                        // Display each user as a table row, with their first name and surname.
                                        echo "<tr onclick='fetchUserDetails(" . $row["id"] . ")'>";
                                        echo "<td>" . htmlspecialchars($row["firstname"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["surname"]) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    // Show message when no users are found.
                                    echo "<tr><td colspan='2'>No users found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- TODO: transaction modal -->
                <div class="right-content">
                <div id="userDisplay">
                    <div id="userDetails" style="display: none;">
                        <center>
                        <img id="userImage" src="../images/default-profile.png" alt="Profile Picture">
                        <br>
                        <div class="user-row">
                            <label>FirstName:</label>
                            <span id="labelFirstname">John</span>
                        </div>
                        <div class="user-row">
                            <label>Surname:</label>
                            <span id="labelSurname">Doe</span>
                        </div>
                        <div class="user-row">
                            <label>Contact:</label>
                            <span id="labelContact">123-456-7890</span>
                        </div>
                        <div class="user-row">
                            <label>Email:</label>
                            <span id="labelEmail">john.doe@example.com</span>
                        </div>
                        <div class="user-row">
                            <!-- <label>Address:</label> -->
                            <span id="labelAddress"></span>
                        </div>
                        <div class="buttons">
                            <!-- Transaction Button -->
                            <!-- Updated Transaction Button -->
                            <button onclick="fetchTransactionDetails(document.getElementById('userId').value)">Transaction</button>
                             <!-- <button onclick="openUpdateModal()">Transaction</button> -->
                            <button class="btn-danger" onclick="deleteUser()">Delete</button>
                        </div>
                        </center>
                    </div>
                </div>  
            </div>


            <!-- Modal for updating user data -->
                <div id="updateModal" class="modal">
                    <div id="modalContent" class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <h2>Transaction Details</h2>
                        <br>
                        <input type="hidden" id="userId" name="userId">
                        <label><b>Reservation ID:</b></label>
                        <p id="reservationId"></p>

                        <label><b>Total Amount:</b></label>
                        <p id="totalAmount"></p>

                        <label><b>Duration (Months):</b></label>
                        <p id="duration"></p>
                        
                        <label><b>Amount Per Month:</b></label>
                        <p id="installmentAmount"></p>


                        <h3>Payment Breakdown</h3>
                        <table border="1">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Due Amount</th>
                                    <th>Payment Status</th>
                                    <th>Print</th>
                                </tr>
                            </thead>
                            <tbody id="paymentBreakdown">
                            </tbody>
                        </table>

                    </div>
                </div>





            <script>
                // TODO: fetch the user details
                function fetchUserDetails(userId) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('GET', 'fetch_user.php?id=' + userId, true);
                    xhr.onload = function() {
                        if (this.status === 200) {
                            const user = JSON.parse(this.responseText);
                            if (user && user.id) {
                                // Update user details
                                document.getElementById("userId").value = user.id;
                                document.getElementById("labelFirstname").textContent = user.firstname;
                                document.getElementById("labelSurname").textContent = user.surname;
                                document.getElementById("labelContact").textContent = user.contact;
                                document.getElementById("labelEmail").textContent = user.email;
                                document.getElementById("labelAddress").textContent = user.address;

                                // Update profile picture
                                const userImage = document.getElementById("userImage");
                                if (user.profile_pic) {
                                    userImage.src = `../uploads/profile_pics/${user.profile_pic}`;
                                } else {
                                    userImage.src = "../images/default-profile.png"; // Fallback image
                                }

                                document.getElementById("userDetails").style.display = 'block';
                            } else {
                                console.error("No user data found");
                            }
                        } else {
                            console.error("Failed to fetch user details. Status:", this.status);
                        }
                    };
                    xhr.send();
                }



                function deleteUser() {
                const userId = document.getElementById("userId").value;
                if (confirm("Are you sure you want to delete this user?")) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'delete_user.php', true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.onload = function() {
                        if (this.status === 200) {
                            alert(this.responseText);
                            location.reload(); // Reload the page to remove the deleted user from the UI
                        } else {
                            console.error("Failed to delete user. Status:", this.status);
                        }
                    };
                    xhr.send("id=" + userId); // Sending user ID as a POST parameter
                }
            }

            // Function to search users based on the input
            function searchUsers() {
                const searchQuery = document.getElementById('search').value;

                // AJAX request to search_users.php
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'search_users.php?query=' + searchQuery, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const users = JSON.parse(xhr.responseText);
                        const userTableBody = document.getElementById('userTableBody');
                        userTableBody.innerHTML = ''; // Clear existing table data

                        if (users.length > 0) {
                            users.forEach(function(user) {
                                const row = document.createElement('tr');
                                row.onclick = function() {
                                    fetchUserDetails(user.id);
                                };
                                row.innerHTML = `
                                    <td>${user.firstname}</td>
                                    <td>${user.surname}</td>
                                `;
                                userTableBody.appendChild(row);
                            });
                        } else {
                            userTableBody.innerHTML = '<tr><td colspan="2">No users found</td></tr>';
                        }
                    } else {
                        console.error('Error fetching users');
                    }
                };
                xhr.send();
            }

            // On page load, display all users
            window.onload = function() {
                searchUsers(); // This will load all users in descending order initially
            };



            function searchUsers() {
            // Get the search input value
            const searchQuery = document.getElementById('search').value;

            // Create an XMLHttpRequest object
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'search_users.php?query=' + encodeURIComponent(searchQuery), true);

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
                            row.setAttribute('onclick', `fetchUserDetails(${user.id})`); // Fetch details on click

                            row.innerHTML = `
                                <td>${user.firstname}</td>
                                <td>${user.surname}</td>
                            `;

                            userTableBody.appendChild(row);  // Add the row to the table body
                        });
                    } else {
                        // If no users found, display message
                        userTableBody.innerHTML = '<tr><td colspan="2">No users found</td></tr>';
                    }
                } else {
                    console.error('Error fetching users:', xhr.status);
                }
            };

            // Send the request to the server
            xhr.send();
        }

        // Automatically load all users on page load
        window.onload = function() {
            searchUsers(); // This will load all users initially
        };

        function openUpdateModal(reservationId) {
        fetch(`fetch_payment_details.php?reservation_id=${reservationId}`)
        .then(response => response.json())
        .then(data => {
            console.log(data); // Debugging

            if (data.success) {
                document.getElementById("reservationId").textContent = data.reservation_id;
                document.getElementById("totalAmount").textContent = data.total_amount;
                document.getElementById("installmentAmount").textContent = 
                    data.installment_amount > 0 ? data.installment_amount : "Not Available";

                // Handle ENUM duration
                let durationText = "";
                if (data.duration === "full") {
                    durationText = "Full Payment";
                } else if (data.duration === "6months") {
                    durationText = "6 Months";
                } else if (data.duration === "9months") {
                    durationText = "9 Months";
                } else {
                    durationText = "Not Available";
                }
                document.getElementById("duration").textContent = durationText;

                // Generate payment breakdown
                let breakdownHTML = "";
                let months = 0;

                if (data.duration === "6months") {
                    months = 6;
                } else if (data.duration === "9months") {
                    months = 9;
                }

                if (months > 0 && data.installment_amount > 0) {
                    for (let i = 1; i <= months; i++) {
                        breakdownHTML += `<tr>
                            <td>Month ${i}</td>
                            <td>${data.installment_amount}</td>
                        </tr>`;
                    }
                } else if (data.duration === "full") {
                    breakdownHTML = "<tr><td colspan='2'>Paid in Full</td></tr>";
                } else {
                    breakdownHTML = "<tr><td colspan='2'>No installment plan available</td></tr>";
                }

                document.getElementById("paymentBreakdown").innerHTML = breakdownHTML;

                document.getElementById("updateModal").style.display = "flex";
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error("Fetch error:", error));
}

function closeModal() {
    document.getElementById("updateModal").style.display = "none";
}


function fetchTransactionDetails(userId) {
    fetch(`fetch_transaction.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                let transaction = data[0]; // Get the first transaction

                document.getElementById("reservationId").textContent = transaction.reservation_id;
                document.getElementById("totalAmount").textContent = "₱ " + parseFloat(transaction.total_amount).toLocaleString();
                document.getElementById("duration").textContent = transaction.duration === "full" ? "Full Payment" : `${transaction.duration} months`;
                document.getElementById("installmentAmount").textContent = transaction.duration !== "full" ? 
                    "₱ " + parseFloat(transaction.installment_amount).toLocaleString() : "N/A";

                generatePaymentBreakdown(transaction, data);

                document.getElementById("updateModal").style.display = "flex";
            } else {
                alert("No transaction details found.");
            }
        })
        .catch(error => console.error("Fetch error:", error));
}

function generatePaymentBreakdown(transaction, payments) {
    const breakdownTable = document.getElementById("paymentBreakdown");
    breakdownTable.innerHTML = "";

    let durationMonths = parseInt(transaction.duration) || 0;
    let installmentAmount = parseFloat(transaction.installment_amount) || 0;

    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    let startDate = new Date(transaction.payment_date);
    let startMonth = startDate.getMonth(); // Get starting month index (0-11)
    let startYear = startDate.getFullYear(); // Get the starting year

    // Extract paid dates from `payments` array
    let paidMonths = payments.map(p => new Date(p.payment_date).getMonth() + "-" + new Date(p.payment_date).getFullYear());

    for (let i = 0; i < durationMonths; i++) {
        let monthIndex = (startMonth + i) % 12;
        let year = startYear + Math.floor((startMonth + i) / 12);
        let monthName = `${monthNames[monthIndex]} ${year}`;
        
        // Check if payment was made for this month-year combination
        let paymentKey = monthIndex + "-" + year;
        let isPaid = paidMonths.includes(paymentKey);

        let printButton = isPaid 
            ? `<button onclick="printReceipt('${transaction.reservation_id}', '${monthName}', ${installmentAmount})" 
                      style="background: white; border: none; cursor: pointer;">
                 🖨️
               </button>` 
            : "";

        let row = `<tr style="background-color: ${isPaid ? 'red' : 'green'}; color: white;">
                      <td>${monthName}</td>
                      <td>₱ ${installmentAmount.toLocaleString()}</td>
                      <td>${isPaid ? "✅ Paid" : "❌ Not Paid"}</td>
                      <td>${printButton}</td>
                   </tr>`;

        breakdownTable.innerHTML += row;
    }
}

function printReceipt(reservationId, monthName, amount) {
    let receiptWindow = window.open('', '', 'width=600,height=400');
    receiptWindow.document.write(`
        <html>
        <head>
            <title>Payment Receipt</title>
        </head>
        <body style="font-family: Arial, sans-serif;">
            <h2>Payment Receipt</h2>
            <p><strong>Reservation ID:</strong> ${reservationId}</p>
            <p><strong>Month Paid:</strong> ${monthName}</p>
            <p><strong>Amount:</strong> ₱ ${amount.toLocaleString()}</p>
            <p><strong>Status:</strong> Paid</p>
            <hr>
            <p>Thank you for your payment!</p>
        </body>
        </html>
    `);
    receiptWindow.document.close();
    receiptWindow.print();
}





            </script>

</body>
</html>



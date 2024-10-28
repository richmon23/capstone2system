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
                        <span><h2>CUSTOMER DATA</h2></span>
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
                <div class="right-content">
                <div id="userDisplay">
                    <div id="userDetails">
                    <img id="userImage" src="../images/female.png" alt="User Image">
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
                            <label>Address:</label>
                            <span id="labelAddress"> Bogo City</span>
                        </div>
                        <button onclick="openUpdateModal()">Edit</button>
                        <button class="btn-danger" onclick="deleteUser()">Delete</button>
                    </div>
                </div>  
            </div>

            <!-- Modal for updating user data -->
            <div id="updateModal" class="modal">
                <div id="modalContent" class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h2>Update User</h2>
                    <br>
                    <form id="userForm">
                        <input type="hidden" id="userId" name="userId">
                        <div class="form-group">
                            <label for="firstname"  class="modallabel">First Name:</label>
                            <input type="text" id="firstname" name="firstname" required>
                        </div>
                        <div class="form-group">
                            <label for="surname" class="modallabel">Surname:</label>
                            <input type="text" id="surname" name="surname" required>
                        </div>
                        <div class="form-group">
                            <label for="contact" class="modallabel">Contact:</label>
                            <input type="text" id="contact" name="contact" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="modallabel">Email:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="address" class="modallabel">Address:</label>
                            <input type="text" id="address" name="address" required>
                        </div>
                        <button type="button" onclick="updateUser()" class="btn-update">Update</button>
                    </form>
                </div>
            </div>


            <script>
                function fetchUserDetails(userId) {
                    const xhr = new XMLHttpRequest();
                    xhr.open('GET', 'fetch_user.php?id=' + userId, true);
                    xhr.onload = function() {
                        if (this.status === 200) {
                            const user = JSON.parse(this.responseText);
                            if (user && user.id) {
                                document.getElementById("userId").value = user.id;
                                document.getElementById("labelFirstname").textContent = user.firstname;
                                document.getElementById("labelSurname").textContent = user.surname;
                                document.getElementById("labelContact").textContent = user.contact;
                                document.getElementById("labelEmail").textContent = user.email;
                                document.getElementById("labelAddress").textContent = user.address;
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

                // Function to open the update modal and populate form fields
            function openUpdateModal() {
                // Fetch the values from userDetails labels
                var firstname = document.getElementById("labelFirstname").innerText;
                var surname = document.getElementById("labelSurname").innerText;
                var contact = document.getElementById("labelContact").innerText;
                var email = document.getElementById("labelEmail").innerText;
                var address = document.getElementById("labelAddress").innerText;

                // Set the values in the modal form
                document.getElementById("firstname").value = firstname;
                document.getElementById("surname").value = surname;
                document.getElementById("contact").value = contact;
                document.getElementById("email").value = email;
                document.getElementById("address").value = address;

                // Display the modal
                document.getElementById("updateModal").style.display = 'block';
            }

            // Function to close the update modal
            function closeModal() {
                document.getElementById("updateModal").style.display = 'none';
            }

            function updateUser() {
                // Get the values from the form inputs
                var userId = document.getElementById("userId").value;
                var firstname = document.getElementById("firstname").value;
                var surname = document.getElementById("surname").value;
                var contact = document.getElementById("contact").value;
                var email = document.getElementById("email").value;
                var address = document.getElementById("address").value;

                // Create a FormData object
                var formData = new FormData();
                formData.append("userId", userId);
                formData.append("firstname", firstname);
                formData.append("surname", surname);
                formData.append("contact", contact);
                formData.append("email", email);
                formData.append("address", address);

                // AJAX request to update the user data
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "update_user.php", true);  // 'update_user.php' is the backend script
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);

                        if (response.success) {
                            alert("User updated successfully!");

                            // Refresh the page to show updated data in the table
                            location.reload();
                        } else {
                            alert("Update failed: " + response.message);
                        }
                    } else {
                        console.error("Failed to update user. Status:", xhr.status);
                    }
                };

                xhr.send(formData);  // Send form data to the server
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

            </script>

</body>
</html>



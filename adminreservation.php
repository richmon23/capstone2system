<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

$firstname = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest';
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';

// Include the database connection
require_once 'connection.php';

// Ensure $pdo is available before using it
if (isset($pdo)) {
    // Handling form submission for create, update, delete
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];

            if ($action == 'create') {
                // Check if all required fields are present
                if (!empty($_POST['fullname']) && !empty($_POST['package']) && !empty($_POST['plotnumber']) && !empty($_POST['blocknumber']) && !empty($_POST['email']) && !empty($_POST['contact']) && !empty($_POST['time'])) {
                    $stmt = $pdo->prepare("INSERT INTO reservation (fullname, package, plotnumber, blocknumber, email, contact, time) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$_POST['fullname'], $_POST['package'], $_POST['plotnumber'], $_POST['blocknumber'], $_POST['email'], $_POST['contact'], $_POST['time']]);
                } else {
                    echo "All fields are required.";
                }
            } elseif ($action == 'update') {
                if (!empty($_POST['id']) && !empty($_POST['fullname']) && !empty($_POST['package']) && !empty($_POST['plotnumber']) && !empty($_POST['blocknumber']) && !empty($_POST['email']) && !empty($_POST['contact']) && !empty($_POST['time'])) {
                    $stmt = $pdo->prepare("UPDATE reservation SET fullname = ?, package = ?, plotnumber = ?, blocknumber = ?, email = ?, contact = ?, time = ? WHERE id = ?");
                    $stmt->execute([$_POST['fullname'], $_POST['package'], $_POST['plotnumber'], $_POST['blocknumber'], $_POST['email'], $_POST['contact'], $_POST['time'], $_POST['id']]);
                } else {
                    echo "All fields are required.";
                }
            } elseif ($action == 'delete') {
                if (!empty($_POST['id'])) {
                    $stmt = $pdo->prepare("DELETE FROM reservation WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                } else {
                    echo "ID is required for deletion.";
                }
            }
        }
    }

    // Fetch data from the database
    $stmt = $pdo->prepare("SELECT * FROM reservation");
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo 'Database connection failed. Please try again later.';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reservation</title>
    <link rel="stylesheet" href="./CSS_FILE/adminreservation.css">
</head>
<body>
    <div class="row">
        <div class="left-content col-3">
            <div class="memoriallogo"><img src="./images/bogomemoriallogo.png" alt="bogomemoriallogo"></div>
            <div class="hamburgermenu"><img src="./images/hamburgermenu.png" alt="hamburgermenu"></div> 
            <div class="adminprofile">
                <center><img src="./images/female.png" alt="adminicon">
                <h2><?php echo $firstname; ?></h2></center>
            </div>
            <center>
                <br>
                <div class="adminlinks">
                    <span><img src="./images/dashboard.png" alt="">&nbsp;&nbsp;<a href="adminDashboard.php">Dashboard</a></span> 
                    <span><img src="./images/deceased.png" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="adminDeceased.php">Deceased</a></span>
                    <span><img src="./images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreservation.php">Reservation</a></span>
                    <span><img src="./images/review.png" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="adminreviews.php">Reviews</a></span>
                    <span><img src="./images/settings.png" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="adminsettings.php">Settings</a></span>
                    <span><img src="./images/payment.png" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="adminpayment.php">Payments</a></span>
                    <br>
                    <span><img src="./images/logout.png" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="logout.php">Logout</a></span>
                </div>
                <br>
            </center>
        </div>
        <div class="main">
            <div class="right-content1">
                <div class="right-header col-9">
                    <br>
                    <span><h1>Reservation</h1></span>
                    <!-- <button onclick="openAddModal()">Add Reservation</button> -->
                </div>
            </div>
            <div class="addreservationvtn">
                <div class="right-header col-9">
                    <br>
                    <!-- <button onclick="openAddModal()"><img src="./images/review.png" alt=""></button> -->
                </div>
            </div>
            <div class="right-content2">
                <div class="right-header col-9">
                    <br>
                    <button class="btnsave "onclick="openAddModal()"><img src="./images/add-user.png" alt=""></button>
                    <table id="myTable">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Package</th>
                                <th>Plot #</th>
                                <th>Block #</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($reservations) && !empty($reservations)): ?>
                                <?php foreach ($reservations as $reservation): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($reservation['fullname']); ?></td>
                                        <td><?php echo htmlspecialchars($reservation['package']); ?></td>
                                        <td><?php echo htmlspecialchars($reservation['plotnumber']); ?></td>
                                        <td><?php echo htmlspecialchars($reservation['blocknumber']); ?></td>
                                        <td><?php echo htmlspecialchars($reservation['email']); ?></td>
                                        <td><?php echo htmlspecialchars($reservation['contact']); ?></td>
                                        <td><?php echo htmlspecialchars($reservation['time']); ?></td>
                                        <td class="actions">
                                            <button class="button update" onclick="openModal(<?php echo htmlspecialchars(json_encode($reservation)); ?>)">Update</button>
                                            <form method="post" style="display:inline-block;">
                                                <input type="hidden" name="id" value="<?php echo $reservation['id']; ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <button type="submit" class="button delete">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="8">No reservations found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Modal for Adding a New Reservation -->
                    <div id="addModal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeAddModal()">&times;</span>
                            <div class="modal-header">
                                <h2>Add New Reservation</h2>
                            </div>
                            <div class="modal-body">
                                <form id="addForm" method="post">
                                    <input type="hidden" name="action" value="create" class="form-element">
                                    <label for="fullname">Full Name:</label><br>
                                    <input type="text" id="add_fullname" name="fullname" required class="form-element"><br><br>
                                    <label for="package">Package:</label><br>
                                    <!-- <input type="text" id="add_package" name="package" required><br><br>
                                    <label for="choices">Choose an option:</label> -->
                                    <select id="add_package"  name="package" class="form-element">
                                        <option value="">Select Package</option>
                                        <option value="garden">Garden</option>
                                        <option value="family_state">Family State</option>
                                        <option value="lawn">Lawn</option>
                                    </select>
                                    <br>
                                    <br>
                                    <label for="plot">Plot #:</label><br>
                                    <input type="text" id="add_plot" name="plotnumber" required class="form-element"><br><br>
                                    <label for="block">Block #:</label><br>
                                    <input type="text" id="add_block" name="blocknumber" required class="form-element"><br><br>
                                    <label for="email">Email:</label><br>
                                    <input type="email" id="add_email" name="email" required class="form-element"><br><br>
                                    <label for="contact">Contact:</label><br>
                                    <input type="text" id="add_contact" name="contact" required class="form-element"><br><br>
                                    <label for="time">Time:</label><br>
                                    <input type="datetime-local" id="add_time" name="time" required class="form-element"><br><br>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button class="button save button-save-modal" onclick="document.getElementById('addForm').submit()">Save</button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal for Update -->
                    <div id="updateModal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeModal()">&times;</span>
                            <div class="modal-header">
                                <h2>Update Details</h2>
                            </div>
                            <div class="modal-body">
                                <form id="updateForm" method="post">
                                    <input type="hidden" name="id" id="modal_id">
                                    <input type="hidden" name="action" value="update">
                                    <label for="fullname">Full Name:</label><br>
                                    <input type="text" id="modal_fullname" name="fullname"><br><br>
                                    <label for="package">Package:</label><br>
                                    <input type="text" id="modal_package" name="package"><br><br>
                                    <label for="plot">Plot #:</label><br>
                                    <input type="text" id="modal_plot" name="plotnumber"><br><br>
                                    <label for="block">Block #:</label><br>
                                    <input type="text" id="modal_block" name="blocknumber"><br><br>
                                    <label for="email">Email:</label><br>
                                    <input type="email" id="modal_email" name="email"><br><br>
                                    <label for="contact">Contact:</label><br>
                                    <input type="text" id="modal_contact" name="contact"><br><br>
                                    <label for="time">Time:</label><br>
                                    <input type="date" id="modal_time" name="time"><br><br>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button class="button update" onclick="document.getElementById('updateForm').submit()">Save</button>
                            </div>
                        </div>
                    </div>

                    <script>
                        var addModal = document.getElementById("addModal");
                        var updateModal = document.getElementById("updateModal");

                        function openAddModal() {
                            addModal.style.display = "block";
                        }

                        function closeAddModal() {
                            addModal.style.display = "none";
                        }

                        function openModal(data) {
                            document.getElementById("modal_id").value = data.id;
                            document.getElementById("modal_fullname").value = data.fullname;
                            document.getElementById("modal_package").value = data.package;
                            document.getElementById("modal_plot").value = data.plotnumber;
                            document.getElementById("modal_block").value = data.blocknumber;
                            document.getElementById("modal_email").value = data.email;
                            document.getElementById("modal_contact").value = data.contact;
                            document.getElementById("modal_time").value = data.time.replace(" ", "T");
                            updateModal.style.display = "block";
                        }

                        function closeModal() {
                            updateModal.style.display = "none";
                        }

                        window.onclick = function(event) {
                            if (event.target == addModal) {
                                closeAddModal();
                            }
                            if (event.target == updateModal) {
                                closeModal();
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

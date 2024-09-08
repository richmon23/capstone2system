
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

$firstname = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest';
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';

// Include the database connection
require_once '../connection/connection.php'; // Include your database connection file

// Ensure $pdo is available before using it
if (isset($pdo)) {
    // Handling form submission for create, update, delete
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];

            if ($action == 'create') {
                // Check if all required fields are present
                if (!empty($_POST['fullname']) && !empty($_POST['address']) && !empty($_POST['born']) && !empty($_POST['died']) && !empty($_POST['plot']) && !empty($_POST['block']) && !empty($_POST['funeralday']) && !empty($_POST['datecreated'])) {
                    $stmt = $pdo->prepare("INSERT INTO deceasedpersoninfo (fullname, address, born, died, plot, block, funeralday, datecreated) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$_POST['fullname'], $_POST['address'], $_POST['born'], $_POST['died'], $_POST['plot'], $_POST['block'], $_POST['funeralday'], $_POST['datecreated']]);
                } else {
                    echo "All fields are required.";
                }
            } elseif ($action == 'update') {
                if (!empty($_POST['fullname']) && !empty($_POST['address']) && !empty($_POST['born']) && !empty($_POST['died']) && !empty($_POST['plot']) && !empty($_POST['block']) && !empty($_POST['funeralday']) && !empty($_POST['datecreated']) && !empty($_POST['id'])) {
                    $stmt = $pdo->prepare("UPDATE deceasedpersoninfo SET fullname = ?, address = ?, born = ?, died = ?, plot = ?, block = ?, funeralday = ?, datecreated = ? WHERE id = ?");
                    $stmt->execute([$_POST['fullname'], $_POST['address'], $_POST['born'], $_POST['died'], $_POST['plot'], $_POST['block'], $_POST['funeralday'], $_POST['datecreated'], $_POST['id']]);
                } else {
                    echo "All fields are required.";
                }
            } elseif ($action == 'delete') {
                if (!empty($_POST['id'])) {
                    $stmt = $pdo->prepare("DELETE FROM deceasedpersoninfo WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                } else {
                    echo "ID is required for deletion.";
                }
            }
        }
    }

    // Fetch data from the database
    $stmt = $pdo->prepare("SELECT * FROM deceasedpersoninfo");
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
    <title>Deceased Person Information</title>
    <link rel="stylesheet" href="./admindashboardcss/admindeceasedinfo.css">
</head> 
<body>

    <!-- <a href="logout.php">Logout</a> -->

    <div class="row">
        <div class="left-content col-4">
            <div class="memoriallogo"><img src="../images/bogomemoriallogo.png" alt="bogomemoriallogo"></div>
            <div class="hamburgermenu"><img src="../images/hamburgermenu.png" alt="hamburgermenu"></div> 
            <div class="adminprofile">
                <center><img src="../images/female.png" alt="adminicon">
                <h2><?php echo $firstname; ?></h2></center>
            </div>
            <center>
                <br>
                <div class="adminlinks">
                            <span><img src="../images/dashboard.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminDashboard.php">Dashboard</a></span> 
                            <span><img src="../images/deceased.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminDeceased.php">Deceased</a></span>
                            <span><img src="../images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreservation.php">Reservation</a></span>
                            <span><img src="../images/review.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminreviews.php">Reviews</a></span>
                            <span><img src="../images/settings.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminsettings.php">Settings</a></span>
                            <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="adminpayment.php">Payments</a></span>
                            <br>
                            <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
                        </div>
                <br>
            </center>
        </div>
        <div class="main">
            <div class="right-content1">
                <br>
                <br>
                <div class="right-header col-9">
                    <span><h1>Deceased Person Information</h1></span>
                </div>
            </div>
            <div class="right-content2">
                <br>
                <button class="btnadd" onclick="openAddModal()"><img src="../images/add-user.png" alt=""></button>
                <table id="myTable">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Address</th>
                            <th>Born</th>
                            <th>Died</th>
                            <th>Plot#</th>
                            <th>Block#</th>
                            <th>Funeral Day</th>
                            <th>Date Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($reservations) && !empty($reservations)): ?>
                            <?php foreach ($reservations as $reservation): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($reservation['fullname']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['address']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['born']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['died']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['plot']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['block']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['funeralday']); ?></td>
                                    <td><?php echo htmlspecialchars($reservation['datecreated']); ?></td>
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
                            <tr><td colspan="9">No reservations found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <!-- Modal for Adding a New Reservation -->
                <div id="addModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeAddModal()">&times;</span>
                        <div class="modal-header">
                            <h2>Add New Data </h2>
                        </div>
                        <div class="modal-body">
                            <form id="addForm" method="post">
                                <input type="hidden" name="action" value="create">
                                <label for="fullname">Full Name:</label><br>
                                <input type="text" id="add_fullname" name="fullname" required class="form-element"><br><br>
                                <label for="address">Address:</label><br>
                                <input type="text" id="add_address" name="address" required class="form-element"><br><br>
                                <label for="born">Born:</label><br>
                                <input type="date" id="add_born" name="born" required class="form-element"><br><br>
                                <label for="died">Died:</label><br>
                                <input type="date" id="add_died" name="died" required class="form-element"><br><br>
                                <label for="plot">Plot #:</label><br>
                                <input type="text" id="add_plot" name="plot" required class="form-element"><br><br>
                                <label for="block">Block #:</label><br>
                                <input type="text" id="add_block" name="block" required class="form-element"><br><br>
                                <label for="funeralday">Funeral Day:</label><br>
                                <input type="date" id="add_funeralday" name="funeralday" required class="form-element"><br><br>
                                <label for="datecreated">Date Created:</label><br>
                                <input type="date" id="add_datecreated" name="datecreated" required class="form-element"><br><br>
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
                                <input type="hidden" name="action" value="update" class="form-element">
                                <label for="fullname">Full Name:</label><br>
                                <input type="text" id="modal_fullname" name="fullname" class="form-element" ><br><br>
                                <label for="address">Address:</label><br>
                                <input type="text" id="modal_address" name="address" class="form-element"><br><br>
                                <label for="born">Born:</label><br>
                                <input type="date" id="modal_born" name="born" class="form-element"><br><br>
                                <label for="died">Died:</label><br>
                                <input type="date" id="modal_died" name="died"class="form-element"><br><br>
                                <label for="plot">Plot #:</label><br>
                                <input type="text" id="modal_plot" name="plot"class="form-element"><br><br>
                                <label for="block">Block #:</label><br>
                                <input type="text" id="modal_block" name="block"class="form-element"><br><br>
                                <label for="funeralday">Funeral Day:</label><br>
                                <input type="date" id="modal_funeralday" name="funeralday"class="form-element"><br><br>
                                <label for="datecreated">Date Created:</label><br>
                                <input type="date" id="modal_datecreated" name="datecreated"class="form-element"><br><br>
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
                        document.getElementById("modal_address").value = data.address;
                        document.getElementById("modal_born").value = data.born;
                        document.getElementById("modal_died").value = data.died;
                        document.getElementById("modal_plot").value = data.plot;
                        document.getElementById("modal_block").value = data.block;
                        document.getElementById("modal_funeralday").value = data.funeralday;
                        document.getElementById("modal_datecreated").value = data.datecreated;

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
</body>
</html>

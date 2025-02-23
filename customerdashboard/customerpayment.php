<?php
// Initialize payments array
$payments = [];

// Database connection
$conn = new mysqli("localhost", "root", "", "capstone2db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch payment records from the database
$sql = "SELECT * FROM payment";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
} else {
    echo "<p style='color: red; text-align: center;'>No payments found.</p>";
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction</title>
    <link rel="stylesheet" href="/customerdashboard/customerdashboard_css/customerpayment.css">
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
                </div>
            </center>
        </div>
        <br>
        <div class="adminlinks">
        <span><img src="../images/dashboard.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerDashboard.php">Dashboard</a></span>
        <span><img src="../images/reservation.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerreservation.php">Reservation</a></span>
        <span><img src="../images/payment.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerpayment.php">Transaction</a></span>
        <span><img src="../images/plot.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerviewavailableplot.php">Available Plot & Block</a></span>
        <span><img src="../images/review.png" alt="">&nbsp;&nbsp;&nbsp;<a href="customerreviews.php">Reviews</a></span>
        <span><img src="../images/logout.png" alt="">&nbsp;&nbsp;&nbsp;<a href="../logout.php">Logout</a></span>
        </div>
        <br>
    </div>
    
    <div class="main">
        <div class="right-content1">
        <div class="right-header col-9">
            <span>PAYMENT</span>
            <br><br><br><br>
            <h2 class="transactionheader">Your Transactions</h2>

            <div class="uppersidebar-content">
                <br>
                <div class="all">
                    <a href="#all" onclick="showContent('content1')"><p>All</p></a>
                </div>
                <div class="cash">
                    <a href="#cash" onclick="showContent('content2', 'cash')"><p>Cash</p></a>
                </div>
                <div class="wired">
                    <a href="#gcash" onclick="showContent('content3', 'GCash')"><p>GCash</p></a>
                </div>
                <div class="installment">
                    <a href="#installment" onclick="showContent('content4', 'Installment')"><p>Installment</p></a>
                </div>
            </div>
        </div>

        </div>

        <div class="right-content2">
            <div class="right-header col-9">

                <!-- All Payments -->
                <div id="content1" class="content" style="display: none;">
                    <br>
                    <table>
                        <tr>
                            <th>Payment ID</th>
                            <th>Total Amount</th>
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th>Date</th>
                        </tr>
                        <?php if (!empty($payments)): ?>
    <?php foreach ($payments as $payment): ?>
        <tr>
            <td><?php echo htmlspecialchars($payment['payment_id']); ?></td>
            <td>$<?php echo htmlspecialchars($payment['total_amount']); ?></td>
            <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
            <td><?php echo htmlspecialchars($payment['payment_status']); ?></td>
            <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="5">No payments found.</td></tr>
<?php endif; ?>

                    </table>
                    <br>
                </div>

                <!-- Cash Payments -->
                <div id="content2" class="content" style="display: none;"></div>

                <!-- GCash Payments -->
                <div id="content3" class="content" style="display: none;"></div>

                <!-- Installment Payments -->
                <div id="content4" class="content" style="display: none;"></div>

            </div>  
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function showContent(contentId, paymentMethod = 'all') {
        $('.content').hide();

        const selectedContent = $('#' + contentId);
        if (selectedContent.length) {
            selectedContent.show();

            if (paymentMethod !== 'all') {
                $.ajax({
                    url: 'fetch_cash_payments.php',
                    type: 'POST',
                    data: { payment_method: paymentMethod },
                    success: function(response) {
                        try {
                            const data = JSON.parse(response);
                            if (data.error) {
                                selectedContent.html(`<p style="color: red; text-align: center;">${data.error}</p>`);
                            } else {
                                let table = '<table border="1">';
                                table += '<tr><th>Payment ID</th><th>Total Amount</th><th>Payment Method</th><th>Payment Status</th><th>Date</th></tr>';
                                data.forEach(function(row) {
                                    table += '<tr>';
                                    table += '<td>' + row.payment_id + '</td>';
                                    table += '<td>$' + row.total_amount + '</td>';
                                    table += '<td>' + row.payment_method + '</td>';
                                    table += '<td>' + row.payment_status + '</td>';
                                    table += '<td>' + row.payment_date + '</td>';
                                    table += '</tr>';
                                });
                                table += '</table>';
                                selectedContent.html(table);
                            }
                        } catch (e) {
                            selectedContent.html("<p style='color: red; text-align: center;'>Error parsing data.</p>");
                        }
                    },
                    error: function() {
                        console.error("Error fetching payments.");
                        selectedContent.html("<p style='color: red; text-align: center;'>Error loading data.</p>");
                    }
                });
            }
        }
    }
</script>

</body>
</html>
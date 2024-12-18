<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

$firstname = isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'Guest';
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';

require_once '../connection/connection.php';


// Check if the request is POST and contains the required data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment_method'])) {
    $payment_method = $_POST['payment_method'];

    try {
        // Query to fetch cash payments
        $stmt = $conn->prepare("SELECT payment_id, total_amount, payment_method, payment_status, payment_date 
                                FROM payment WHERE payment_method = :payment_method");
        $stmt->bindParam(':payment_method', $payment_method, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Generate table rows dynamically
        if ($result) {
            echo '<table>';
            echo '<tr>
                    <th>Payment ID</th>
                    <th>Total Amount</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Date</th>
                  </tr>';
            foreach ($result as $row) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['payment_id']) . '</td>';
                echo '<td>$' . htmlspecialchars($row['total_amount']) . '</td>';
                echo '<td>' . htmlspecialchars($row['payment_method']) . '</td>';
                echo '<td>' . htmlspecialchars($row['payment_status']) . '</td>';
                echo '<td>' . htmlspecialchars($row['payment_date']) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>No records found for Cash payments.</p>';
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}

?>
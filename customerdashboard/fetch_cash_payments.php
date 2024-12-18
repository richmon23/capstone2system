<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

require_once '../connection/connection.php';

// Check if the request is POST and contains the required data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment_method'])) {
    $payment_method = $_POST['payment_method'];
    $userId = $_SESSION['user_id'];

    try {
        // Fetch payments where reservation_id is linked to the logged-in user
        $stmt = $conn->prepare("
            SELECT p.payment_id, p.total_amount, p.payment_method, p.payment_status, p.payment_date 
            FROM payment AS p
            INNER JOIN reservation AS r ON p.reservation_id = r.id
            WHERE p.payment_method = :payment_method AND r.user_id = :user_id
        ");
        $stmt->bindParam(':payment_method', $payment_method, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
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
            echo '<p>No records found for the selected payment method.</p>';
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>

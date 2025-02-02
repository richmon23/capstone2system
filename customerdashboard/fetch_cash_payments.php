<?php
session_start();
require_once '../connection/connection.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p style='color: red; text-align: center;'>User not logged in.</p>";
    exit();
}

// Ensure POST request with a valid payment method
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment_method'])) {
    $payment_method = $_POST['payment_method'];
    $userId = $_SESSION['user_id'];

    $query = "
        SELECT p.payment_id, p.total_amount, p.payment_method, p.payment_status, p.payment_date 
        FROM payment AS p
        INNER JOIN reservation AS r ON p.reservation_id = r.id
        WHERE r.user_id = :user_id";
    
    if ($payment_method !== 'all') {
        $query .= " AND p.payment_method = :payment_method";
    }

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

    if ($payment_method !== 'all') {
        $stmt->bindParam(':payment_method', $payment_method, PDO::PARAM_STR);
    }

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        echo '<table border="1">';
        echo '<tr><th>Payment ID</th><th>Total Amount</th><th>Payment Method</th><th>Payment Status</th><th>Date</th></tr>';
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
        echo "<p style='color: red; text-align: center;'>No records found for $payment_method payments.</p>";
    }
}

?>

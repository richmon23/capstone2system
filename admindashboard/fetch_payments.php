<?php
require_once '../connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
    $paymentMethod = $_POST['payment_method'];

    try {
        $stmt = $conn->prepare("SELECT payment_id, total_amount, payment_method, payment_status, payment_date 
                                FROM payment 
                                WHERE payment_method = :paymentMethod");
        $stmt->bindParam(':paymentMethod', $paymentMethod, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Generate the table dynamically
        if ($result) {
            echo "<table>
                    <tr>
                        <th>Payment ID</th>
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Date</th>
                    </tr>";
            foreach ($result as $row) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['payment_id']) . "</td>
                        <td>" . htmlspecialchars($row['total_amount']) . "</td>
                        <td>" . htmlspecialchars($row['payment_method']) . "</td>
                        <td>" . htmlspecialchars($row['payment_status']) . "</td>
                        <td>" . htmlspecialchars($row['payment_date']) . "</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No payments found for $paymentMethod.</p>";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

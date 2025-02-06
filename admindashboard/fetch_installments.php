<?php
require_once '../connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fetch_installments'])) {
    try {
        // Check if the database connection is working
        if (!$conn) {
            throw new Exception("Database connection failed.");
        }

        // Prepare SQL query to fetch only records where 'installment_plan' is not null or empty
        $stmt = $conn->prepare("SELECT payment_id, total_amount, payment_method, payment_status, payment_date 
                                FROM payment 
                               WHERE installment_plan = 'installment'");

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
            echo "<p>No installment payments found.</p>";
        }
    } catch (Exception $e) {
        // Catch and display any errors
        echo "Error: " . $e->getMessage();
    }
}
?>

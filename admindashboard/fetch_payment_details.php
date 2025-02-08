<?php
require_once '../connection/connection.php';
header('Content-Type: application/json');

if (isset($_GET['reservation_id'])) {
    $reservation_id = intval($_GET['reservation_id']); // Ensure it's an integer

    try {
        $query = "SELECT reservation_id, total_amount, installment_amount, installment_plan 
                  FROM payment WHERE reservation_id = :reservation_id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo json_encode([
                "success" => true,
                "reservation_id" => $row['reservation_id'],
                "total_amount" => $row['total_amount'],
                "installment_amount" => $row['installment_amount'],
                "installment_plan" => $row['installment_plan']
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "No payment data found."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Missing reservation_id."]);
}
?>

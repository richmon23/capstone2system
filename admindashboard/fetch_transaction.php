<?php
require_once '../connection/connection.php';


if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    try {
        $stmt = $conn->prepare("
            SELECT p.reservation_id, p.total_amount, p.installment_amount, p.duration, p.payment_date, p.payment_status
            FROM payment p
            JOIN reservation r ON p.reservation_id = r.id
            WHERE r.user_id = :user_id
            ORDER BY p.payment_date ASC
        ");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode(["error" => "No transaction found."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}

?>

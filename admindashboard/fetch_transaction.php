<?php
require_once '../connection/connection.php';

if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);

    try {
        $stmt = $conn->prepare("
            SELECT 
                p.reservation_id, 
                p.total_amount, 
                p.installment_amount, 
                p.duration, 
                p.payment_date, 
                p.payment_status, 
                p.created_at, 
                p.installment_plan, 
                p.remaining_balance
            FROM payment p
            JOIN reservation r ON p.reservation_id = r.id
            WHERE r.user_id = :user_id 
            ORDER BY p.payment_date ASC
        ");

        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            foreach ($result as &$row) {
                // Convert duration to months
                $row['duration_months'] = ($row['duration'] === 'full') ? "0 months" : intval(preg_replace('/[^0-9]/', '', $row['duration'])) . " months";

                // Set remaining balance display
                $row['remaining_balance_display'] = ($row['remaining_balance'] == 0.00) 
                    ? "Fully Paid" 
                    : "₱ " . number_format($row['remaining_balance'], 2);

                // Format payment date properly
                $row['payment_date_display'] = ($row['payment_date'] !== null) ? $row['payment_date'] : "Not Paid";

                // Handle installment due dates
                if ($row['installment_plan'] !== 'fullpayment') {
                    $row['due_dates'] = calculateDueDates($row['payment_date'], $row['duration']);
                }
            }

            echo json_encode($result);
        } else {
            echo json_encode(["error" => "No transactions found."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Invalid or missing user ID."]);
}

// Function to calculate due dates for installment plans
function calculateDueDates($startDate, $duration) {
    $dueDates = [];
    $months = intval(preg_replace('/[^0-9]/', '', $duration));

    if ($months > 0 && !empty($startDate)) {
        $startDate = new DateTime($startDate);

        for ($i = 1; $i <= $months; $i++) {
            $dueDate = clone $startDate;
            $dueDate->modify("+$i month");

            $dueDates[] = [
                "due_date" => $dueDate->format('Y-m-d'),
                "payment_status" => "not_paid",
                "payment_date" => "Not Paid"
            ];
        }
    }

    return $dueDates;
}
?>

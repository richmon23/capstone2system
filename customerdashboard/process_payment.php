<?php
session_start();
require_once '../connection/connection.php';

// Set JSON header for response
header('Content-Type: application/json');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the action is 'installment'
    if (isset($_POST['action']) && $_POST['action'] == 'installment') {
        // Fetch installment data from the payment table
        try {
            $stmt = $conn->prepare("SELECT payment_id, reservation_id, client_name, payment_date, payment_method, payment_status, total_amount, duration, installment_amount, amount_paid, payment_proof, package, installment_plan, fullpayment_amount, status FROM payment WHERE payment_id = :payment_id");
            $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();
            $installments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($installments) {
                echo json_encode(['status' => 'success', 'months' => $installments]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No installment data found.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
        exit();
    }

    // Retrieve form data with checks
    $reservation_id = isset($_POST['reservation_id']) ? $_POST['reservation_id'] : null;
    $firstname = isset($_POST['firstname']) ? $_POST['firstname'] : null;
    $surname = isset($_POST['surname']) ? $_POST['surname'] : null;
    $package = isset($_POST['package']) ? $_POST['package'] : null;
    $block = isset($_POST['block']) ? $_POST['block'] : null;
    $plot = isset($_POST['plot']) ? $_POST['plot'] : null;
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : null;
    $installment_plan = isset($_POST['installment_plan']) ? $_POST['installment_plan'] : '';
    $duration = isset($_POST['duration']) ? $_POST['duration'] : '';
    $payment_proof = null;

    // Initialize payment variables
    $amount_paid = 0.00;
    $installment_amount = 0.00;
    $total_amount = 0.00;
    $payment_status = 'notpaid';
    $fullpayment_amount = 0.00;  // To store full payment amount

    // Convert package to lowercase for case-insensitive comparison
    $package = strtolower($package);

    // Example pricing logic (adjust based on your needs)
    if ($package == 'lawn') {
        $total_amount = 20000.00;  // Lawn package amount
        $fullpayment_amount = $total_amount; // Set full payment amount
    } elseif ($package == 'garden') {
        $total_amount = 30000.00;
        $fullpayment_amount = $total_amount;
    } elseif ($package == 'family state') {
        $total_amount = 50000.00;
        $fullpayment_amount = $total_amount;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid package selected.']);
        exit();
    }

    // Handle installment logic if applicable
    if ($installment_plan == 'installment') {
        // Determine installment amount and duration
        if ($duration == '6months') {
            $installment_amount = $total_amount / 6;
        } elseif ($duration == '9months') {
            $installment_amount = $total_amount / 9;
        }
    } else {
        // Full payment, set amount_paid to the fullpayment_amount
        $amount_paid = $fullpayment_amount;  // Use full payment amount if it's full payment
    }

    // Handle file upload for payment proof (only for GCash)
    if ($payment_method == 'gcash' || isset($_FILES['payment_proof'])) {
        if ($_FILES['payment_proof']['error'] == 0) {
            $target_dir = "../uploads/payment_proofs/";
            $file_name = time() . "_" . basename($_FILES['payment_proof']['name']);
            $target_file = $target_dir . $file_name;

            // Create directory if it doesn't exist
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $target_file)) {
                $payment_proof = $target_file;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload the payment proof.']);
                exit();
            }
        }
    }

    // Handle cash payment status and amount
    if ($payment_method == 'gcash') {
        $payment_status = 'paid';
        $amount_paid = $fullpayment_amount; // For GCash, set to the full payment amount
    } elseif ($payment_method == 'cash') {
        $payment_status = 'paid'; // Cash payment is also automatically marked as paid
        $amount_paid = $fullpayment_amount; // Full amount for cash payments
    }

    // Prepare the SQL query to insert payment details
    try {
        // Begin a transaction
        $conn->beginTransaction();

        // Prepare the insert statement
        $stmt = $conn->prepare("INSERT INTO payment (reservation_id, client_name, package, payment_method, payment_status, amount_paid, installment_plan, duration, total_amount, installment_amount, fullpayment_amount, payment_proof, payment_date) 
            VALUES (:reservation_id, :client_name, :package, :payment_method, :payment_status, :amount_paid, :installment_plan, :duration, :total_amount, :installment_amount, :fullpayment_amount, :payment_proof, NOW())");

        // Bind parameters by reference
        $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
        $stmt->bindParam(':client_name', $client_name);
        $client_name = $firstname . ' ' . $surname;
        $stmt->bindParam(':package', $package, PDO::PARAM_STR);
        $stmt->bindParam(':payment_method', $payment_method, PDO::PARAM_STR);
        $stmt->bindParam(':payment_status', $payment_status, PDO::PARAM_STR);
        $stmt->bindParam(':amount_paid', $amount_paid, PDO::PARAM_STR);
        $stmt->bindParam(':installment_plan', $installment_plan, PDO::PARAM_STR);
        $stmt->bindParam(':duration', $duration, PDO::PARAM_STR);
        $stmt->bindParam(':total_amount', $total_amount, PDO::PARAM_STR);
        $stmt->bindParam(':installment_amount', $installment_amount, PDO::PARAM_STR);
        $stmt->bindParam(':fullpayment_amount', $fullpayment_amount, PDO::PARAM_STR);  // Ensure fullpayment_amount is inserted
        $stmt->bindParam(':payment_proof', $payment_proof, PDO::PARAM_STR);

        // Execute the statement
        $stmt->execute();

        // Update the reservation status if payment is completed
        if ($payment_status == 'paid') {
            $updateReservationStatus = $conn->prepare("UPDATE reservation SET status = 'success' WHERE id = :reservation_id");
            $updateReservationStatus->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
            $updateReservationStatus->execute();
        }

        // Commit the transaction
        $conn->commit();

        // Return success response
        echo json_encode(['status' => 'success', 'message' => 'Payment processed successfully.']);
        exit();

    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}
?>
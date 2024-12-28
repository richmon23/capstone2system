<?php
// Include database connection
require_once '../connection/connection.php'; // Include your database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $reservation_id = $_POST['reservation_id'];
    $firstname = $_POST['firstname'];
    $surname = $_POST['surname'];
    $package = $_POST['package'];
    $block = $_POST['block'];
    $plot = $_POST['plot'];
    $payment_method = $_POST['payment_method'];
    $installment_plan = isset($_POST['installment_plan']) ? $_POST['installment_plan'] : '';
    $duration = isset($_POST['duration']) ? $_POST['duration'] : '';
    $payment_proof = null;

    // Debugging package value
    echo "Package selected: " . $package . "<br>";

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
        echo "Error: Invalid package selected.";
    }

    // Debugging to ensure fullpayment_amount is assigned correctly
    echo "Full Payment Amount: " . $fullpayment_amount . "<br>";

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

    // Debugging the amount paid
    echo "Amount Paid: " . $amount_paid . "<br>";

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
                echo "File uploaded successfully: " . $payment_proof . "<br>";
            } else {
                echo "Failed to upload the payment proof.";
            }
        }
    }

    if ($payment_method == 'gcash') {
        // GCash payment is considered automatically paid
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

        // Redirect or display success message
        echo "Payment processed successfully.";
        // Redirect to a confirmation page or back to the reservations page
        header("Location: customerreservation.php");
        exit;

    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

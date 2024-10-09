<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include your database connection using PDO
require_once '../connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Start a transaction
    $pdo->beginTransaction();

    try {
        // Prepare the SQL update statement
        $sql = "UPDATE reservation SET status = :status" . 
               ($status === 'approved' ? ", approved_time = NOW()" : "") . 
               " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Debugging output for the status
        echo "Received status: $status for ID: $id\n";

        if ($stmt->execute()) {
            // If the status is 'approved', fetch and insert into approvedaccount table
            if ($status === 'approved') {
                // Fetch relevant data from the reservation table
                $fetchSql = "SELECT package, plotnumber, blocknumber FROM reservation WHERE id = :id";
                $fetchStmt = $pdo->prepare($fetchSql);
                $fetchStmt->bindParam(':id', $id, PDO::PARAM_INT);
                $fetchStmt->execute();
                $reservationData = $fetchStmt->fetch(PDO::FETCH_ASSOC);

                if ($reservationData) {
                    // Insert data into the approvedaccount table
                    $insertSql = "INSERT INTO approvedaccount (reservation_id, approval_date, package, plotnumber, blocknumber, status)
                                  VALUES (:reservation_id, NOW(), :package, :plotnumber, :blocknumber, :status)";
                    $insertStmt = $pdo->prepare($insertSql);
                    $insertStmt->bindParam(':reservation_id', $id, PDO::PARAM_INT);
                    $insertStmt->bindParam(':package', $reservationData['package'], PDO::PARAM_STR);
                    $insertStmt->bindParam(':plotnumber', $reservationData['plotnumber'], PDO::PARAM_INT);
                    $insertStmt->bindParam(':blocknumber', $reservationData['blocknumber'], PDO::PARAM_INT);
                    $insertStmt->bindParam(':status', $status, PDO::PARAM_STR);

                    if ($insertStmt->execute()) {
                        // Commit the transaction if everything went well
                        $pdo->commit();
                        header("Location: admindashboard.php?message=Status updated and account approved successfully");
                        exit;
                    } else {
                        throw new Exception("Error inserting approved account.");
                    }
                } else {
                    throw new Exception("Error fetching reservation details.");
                }
            } else {
                // Commit the transaction for disapproved status
                $pdo->commit();
                header("Location: admindashboard.php?message=Status updated successfully");
                exit;
            }
        } else {
            echo "Error updating status: ";
            print_r($stmt->errorInfo());
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>

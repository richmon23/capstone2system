<?php
// Include the database connection
require_once '../connection/connection.php'; // Ensure this file contains a valid PDO connection

// Check if block is set in the request
if (isset($_GET['block'])) {
    $block = intval($_GET['block']);  // Get block number from the request

    try {
        // Fetch all plots for the selected block
        $sql = "SELECT plot_number, is_available FROM plots WHERE block = :block ORDER BY plot_number ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":block", $block, PDO::PARAM_INT);
        $stmt->execute();
        $plots = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return JSON response
        echo json_encode($plots);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
}
?>

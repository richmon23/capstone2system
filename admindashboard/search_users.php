<?php
require_once '../connection/connection.php'; // Database connection

// Retrieve the search query, or default to an empty string
$search = isset($_GET['query']) ? $_GET['query'] : '';

try {
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare the SQL query based on the search input
    if ($search) {
        $sql = "SELECT * FROM users WHERE firstname LIKE :search OR surname LIKE :search ORDER BY id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['search' => '%' . $search . '%']);
    } else {
        // Default to show all users in descending order when no search term is entered
        $sql = "SELECT * FROM users ORDER BY id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    // Fetch the results
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the data as JSON
    echo json_encode($users);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

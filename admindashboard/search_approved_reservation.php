<?php
session_start();
require_once '../connection/connection.php';

// Get the search query from the GET parameters
$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';

// Prepare the SQL query to search approved reservations by fullname
$sql = "SELECT firstname, package, plotnumber, blocknumber, status 
        FROM reservation 
        WHERE status = 'approved' 
        AND firstname LIKE :searchQuery";

// Prepare the statement
$stmt = $conn->prepare($sql);
$stmt->bindValue(':searchQuery', '%' . $searchQuery . '%', PDO::PARAM_STR);

// Execute the statement
$stmt->execute();

// Fetch all matching results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the results as JSON
header('Content-Type: application/json');
echo json_encode($results);
?>

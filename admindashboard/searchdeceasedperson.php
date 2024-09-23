<?php
// Include your database connection using PDO
require_once '../connection/connection.php'; // Include your database connection file

if (isset($_POST['query'])) {
    $search = "%" . $_POST['query'] . "%";
    
    // Prepare the SQL query to only search by 'fullname'
    $sql = "SELECT * FROM deceasedpersoninfo WHERE fullname LIKE :search";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':search', $search, PDO::PARAM_STR);
} else {
    // If no search query is provided, fetch all records
    $sql = "SELECT * FROM deceasedpersoninfo ORDER BY datecreated DESC";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($reservations) > 0) {
    foreach ($reservations as $reservation) {
        echo '
<tr>
    <td>' . htmlspecialchars($reservation["fullname"]) . '</td>
    <td>' . htmlspecialchars($reservation["address"]) . '</td>
    <td>' . htmlspecialchars($reservation["born"]) . '</td>
    <td>' . htmlspecialchars($reservation["died"]) . '</td>
    <td>' . htmlspecialchars($reservation["plot"]) . '</td>
    <td>' . htmlspecialchars($reservation["block"]) . '</td>
    <td>' . htmlspecialchars($reservation["funeralday"]) . '</td>
    <td>' . htmlspecialchars($reservation["datecreated"]) . '</td>
    <td class="actions">
        <button class="button update" onclick=\'openModal(' . json_encode($reservation, JSON_HEX_APOS | JSON_HEX_QUOT) . ')\'>Update</button>
        <form method="post" style="display:inline-block;" onsubmit="return confirmDelete()">
            <input type="hidden" name="id" value="' . $reservation['id'] . '">
            <input type="hidden" name="action" value="delete">
            <button type="submit" class="button delete">Delete</button>
        </form>
    </td>
</tr>';

    }
} else {
    echo '<tr><td colspan="8">No reservations found.</td></tr>';
}
?>

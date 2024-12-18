<?php
// Include your database connection using PDO
require_once '../connection/connection.php';

if (isset($_POST['query'])) {
    $search = "%" . $_POST['query'] . "%";
    
    // Prepare the SQL query with separate placeholders for firstname and surname
    $sql = "SELECT * FROM deceasedpersoninfo WHERE firstname LIKE :firstname OR surname LIKE :surname";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':firstname', $search, PDO::PARAM_STR);
    $stmt->bindParam(':surname', $search, PDO::PARAM_STR);
} else {
    // If no search query is provided, fetch all records
    $sql = "SELECT * FROM deceasedpersoninfo ORDER BY datecreated DESC";
    $stmt = $pdo->prepare($sql);
}

// $sql = "SELECT * FROM deceasedpersoninfo ORDER BY datecreated ASC";

$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($reservations) > 0) {
    foreach ($reservations as $reservation) {
        echo '
<tr>
    <td>' . htmlspecialchars($reservation["firstname"]) . '</td>
    <td>' . htmlspecialchars($reservation["surname"]) . '</td>
    <td>' . htmlspecialchars($reservation["province"]) . '</td>
    <td>' . htmlspecialchars($reservation["municipality"]) . '</td>
    <td>' . htmlspecialchars($reservation["completeaddress"]) . '</td>
    <td>' . htmlspecialchars($reservation["born"]) . '</td>
    <td>' . htmlspecialchars($reservation["died"]) . '</td>
    <td>' . htmlspecialchars($reservation["plot"]) . '</td>
    <td>' . htmlspecialchars($reservation["block"]) . '</td>
    <td>' . htmlspecialchars($reservation["funeralday"]) . '</td>
   
    <td class="actions">
        <button class="button update" onclick=\'openModal(' . json_encode($reservation, JSON_HEX_APOS | JSON_HEX_QUOT) . ')\'>
            <i class="fas fa-edit"></i> <!-- Update icon -->
        </button>
        <form method="post" style="display:inline-block;" onsubmit="return confirmDelete()">
            <input type="hidden" name="id" value="' . $reservation['id'] . '">
            <input type="hidden" name="action" value="delete">
            <button type="submit" class="button delete">
                <i class="fas fa-trash-alt"></i> <!-- Delete icon -->
            </button>
        </form>
    </td>
</tr>';
    }
} else {
    echo '<tr><td colspan="12">No Information found.</td></tr>';
}
?>

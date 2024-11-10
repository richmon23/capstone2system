<?php
// Include your database connection using PDO
require_once '../connection/connection.php';

if (isset($_POST['query'])) {
    $search = "%" . $_POST['query'] . "%";
    
    // Prepare the SQL query to only search by 'fullname'
    $sql = "SELECT *, status FROM reservation WHERE firstname LIKE :search";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':search', $search, PDO::PARAM_STR);
} else {
    // If no search query is provided, fetch all records
    $sql = "SELECT *, status FROM reservation";
    $stmt = $pdo->prepare($sql);
}

$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($reservations) > 0) {
    foreach ($reservations as $reservation) {
        ?>
        <tr>
            <td><?= htmlspecialchars($reservation["firstname"]); ?></td>
            <td><?= htmlspecialchars($reservation["surname"]); ?></td>
            <td><?= htmlspecialchars($reservation["package"]); ?></td>
            <td><?= htmlspecialchars($reservation["plotnumber"]); ?></td>
            <td><?= htmlspecialchars($reservation["blocknumber"]); ?></td>
            <td><?= htmlspecialchars($reservation["email"]); ?></td>
            <td><?= htmlspecialchars($reservation["contact"]); ?></td>
            <td><?= htmlspecialchars($reservation["time"]); ?></td>
            <td><?= htmlspecialchars($reservation["status"]); ?></td>
            
            <td class="actions">
                <!-- Update button with an icon -->
                <button class="button update" title="update" onclick='openModal(<?= json_encode($reservation, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                    <i class="fas fa-edit"></i>
                </button>

                <!-- Delete button with an icon -->
                <form method="post" style="display:inline-block;" onsubmit="return confirmDelete()">
                    <input type="hidden" name="id" value="<?= $reservation['id']; ?>">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" title="delete" class="button delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>

                <!-- Conditionally hide the Status button if the reservation is approved -->
                <?php if (strtolower(trim($reservation["status"])) !== "approved"): ?>
                    <button class="button status" title="status" onclick='openStatusModal(<?= json_encode($reservation, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'>
                        <i class="fas fa-info-circle"></i>
                    </button>
                <?php endif; ?>

                <!-- Payment button with inline style -->
                <button class="button payment" title="payment"
    onclick="openPaymentModal(<?= $reservation['id'] ?>, '<?= htmlspecialchars($reservation['firstname'], ENT_QUOTES) ?>', '<?= htmlspecialchars($reservation['surname'], ENT_QUOTES) ?>', '<?= htmlspecialchars($reservation['package'], ENT_QUOTES) ?>', '<?= htmlspecialchars($reservation['blocknumber'], ENT_QUOTES) ?>', '<?= htmlspecialchars($reservation['plotnumber'], ENT_QUOTES) ?>')"
    style="background-color: teal; border: none; color: white; padding: 10px 15px; border-radius: 5px; cursor: pointer;">
    <i class="fas fa-money-bill" style="color: white;"></i>
</button>


            </td>
        </tr>
        <?php
    }
} else {
    echo '<tr><td colspan="9">No reservations found.</td></tr>';
}
?>

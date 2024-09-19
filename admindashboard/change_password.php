<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: userlogin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Include the database connection
require_once '../connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if required fields are set in $_POST
    if (isset($_POST['currentPassword'], $_POST['newPassword'], $_POST['confirmPassword'])) {
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];

        // Validate that new passwords match
        if ($newPassword !== $confirmPassword) {
            header("Location: adminDashboard.php?error=Passwords do not match");
            exit();
        }

        // Fetch the current password from the database for verification
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($currentPassword, $user['password'])) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            if ($updateStmt->execute([$hashedPassword, $user_id])) {
                header("Location: adminDashboard.php?success=Password updated successfully");
                exit();
            } else {
                header("Location: adminDashboard.php?error=Failed to update the password. Please try again.");
                exit();
            }
        } else {
            header("Location: adminDashboard.php?error=Current password is incorrect");
            exit();
        }
    } else {
        header("Location: adminDashboard.php?error=All fields are required");
        exit();
    }
}
?>

<?php
// filepath: d:\agstation\admin\payments\delete.php
require_once '../../includes/session.php';
require_once '../../config/db.php';

// Ensure the user has admin privileges (case-insensitive)
checkRole(['Admin', 'admin', 1]); // Accepts role name or role id

// Validate and sanitize the ID parameter
$payment_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$payment_id) {
    header("Location: index.php");
    exit;
}

try {
    // Delete the payment from the database
    $query = "DELETE FROM Payments WHERE payment_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$payment_id]);
    header("Location: index.php");
    exit;
} catch (PDOException $e) {
    die("Error deleting payment: " . $e->getMessage());
}
?>

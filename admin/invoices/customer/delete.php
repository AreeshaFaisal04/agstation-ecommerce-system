<?php
// filepath: d:\agstation\admin\invoices\customer\delete.php
include '../../../includes/session.php';
include '../../../config/db.php';

// Ensure the user has admin privileges (case-insensitive)
checkRole(['Admin', 'admin', 1]); // Accepts role name or role id

// Validate and sanitize the ID parameter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

try {
    // Delete the customer invoice from the database
    $query = "DELETE FROM CustomerInvoices WHERE invoice_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    header("Location: index.php");
    exit;
} catch (PDOException $e) {
    die("Error deleting customer invoice: " . $e->getMessage());
}
?>

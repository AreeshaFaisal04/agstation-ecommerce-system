<?php
// filepath: d:\agstation\admin\orders\delete.php
include '../../includes/session.php';
include '../../config/db.php';

// Ensure the user has admin privileges (case-insensitive)
checkRole(['Admin', 'admin', 1]); // Accepts role name or role id

// Validate and sanitize the ID parameter
$order_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$order_id) {
    header("Location: index.php");
    exit;
}

try {
    // Delete the order from the database
    $query = "DELETE FROM WebsiteOrders WHERE order_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$order_id]);
    header("Location: index.php");
    exit;
} catch (PDOException $e) {
    die("Error deleting order: " . $e->getMessage());
}
?>

<?php
// filepath: d:\agstation\admin\inventory\delete.php
include '../../includes/session.php';
include '../../config/db.php';

// Ensure the user has admin privileges (case-insensitive)
checkRole(['Admin', 'admin', 1]); // Accepts role name or role id

// Validate and sanitize the ID parameter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

try {
    // Delete the inventory entry from the database
    $query = "DELETE FROM Inventory WHERE inventory_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    header("Location: index.php");
    exit;
} catch (PDOException $e) {
    die("Error deleting inventory entry: " . $e->getMessage());
}
?>

<?php
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
    // Delete the customer from the database
    $stmt = $pdo->prepare("DELETE FROM WebsiteCustomers WHERE customer_id = ?");
    $stmt->execute([$id]);
} catch (PDOException $e) {
    // Optionally, handle errors (e.g., show a message or log)
    // die("Error deleting customer: " . $e->getMessage());
}

header("Location: index.php");
exit;
?>
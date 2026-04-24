<?php
include_once '../../includes/session.php';
include_once '../../config/db.php';

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || ($_SESSION['role_id'] ?? null) != 1) {
    header("Location: /auth/login.php");
    exit;
}

// Validate and get the brand ID
$brand_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$brand_id) {
    header("Location: index.php");
    exit;
}

try {
    // Delete the brand
    $stmt = $pdo->prepare("DELETE FROM Brands WHERE brand_id = ?");
    $stmt->execute([$brand_id]);
} catch (PDOException $e) {
    // Optionally, handle errors (e.g., show a message or log)
}

header("Location: index.php");
exit;

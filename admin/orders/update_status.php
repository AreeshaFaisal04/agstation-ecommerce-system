<?php 
include '../../includes/header.php';
include '../../includes/session.php';
checkRole(['admin']);
require_once '../../config/db.php';

// Validate and sanitize the order ID
$order_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$order_id) {
    header("Location: index.php");
    exit;
}

try {
    // Fetch the order details
    $query = "SELECT * FROM orders WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        header("Location: index.php");
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_status = trim($_POST['status']);
        $update_query = "UPDATE orders SET status = ? WHERE id = ?";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([$new_status, $order_id]);
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h3>Update Status for Order #<?= htmlspecialchars($order['id']) ?></h3>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Current Status: <?= ucfirst(htmlspecialchars($order['status'])) ?></label>
            <select name="status" class="form-select" required>
                <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update Status</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>

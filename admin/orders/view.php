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
    // Fetch order details
    $order_query = "
        SELECT o.id, u.username, o.total_amount, o.status, o.created_at 
        FROM orders o 
        JOIN users u ON o.customer_id = u.id 
        WHERE o.id = ?
    ";
    $stmt = $pdo->prepare($order_query);
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        header("Location: index.php");
        exit;
    }

    // Fetch order items
    $items_query = "
        SELECT p.name, oi.quantity, oi.unit_price 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = ?
    ";
    $items_stmt = $pdo->prepare($items_query);
    $items_stmt->execute([$order_id]);
    $items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h3>Order #<?= htmlspecialchars($order['id']) ?> Details</h3>
    <p><strong>Customer:</strong> <?= htmlspecialchars($order['username']) ?></p>
    <p><strong>Total:</strong> Rs. <?= htmlspecialchars($order['total_amount']) ?></p>
    <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($order['status'])) ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($order['created_at']) ?></p>

    <h4>Items</h4>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= htmlspecialchars($item['quantity']) ?></td>
                <td>Rs. <?= htmlspecialchars($item['unit_price']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="update_status.php?id=<?= htmlspecialchars($order_id) ?>" class="btn btn-primary">Update Status</a>
</div>

<?php include '../../includes/footer.php'; ?>

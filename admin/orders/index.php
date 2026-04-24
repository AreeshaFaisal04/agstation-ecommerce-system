<?php
include '../../includes/session.php';
include '../../includes/header.php';
require_once '../../config/db.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';

checkRole(['admin']);

try {
    // Fetch orders with customer details
    $query = "
        SELECT o.id, u.username, o.total_amount, o.status, o.created_at 
        FROM orders o
        JOIN users u ON o.customer_id = u.id
        ORDER BY o.created_at DESC
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2>Customer Orders</h2>
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?= htmlspecialchars($order['id']) ?></td>
                <td><?= htmlspecialchars($order['username']) ?></td>
                <td>Rs. <?= htmlspecialchars($order['total_amount']) ?></td>
                <td><?= ucfirst(htmlspecialchars($order['status'])) ?></td>
                <td><?= date('d-M-Y', strtotime($order['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>

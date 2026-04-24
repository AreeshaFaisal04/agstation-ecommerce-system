<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';

try {
    // Fetch payments with related order details
    $query = "
        SELECT p.payment_id, p.order_id, p.amount, p.method, p.status, p.payment_date, o.customer_id 
        FROM Payments p
        JOIN WebsiteOrders o ON p.order_id = o.order_id
        ORDER BY p.payment_date DESC
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container">
    <h2>Payments</h2>
    <a href="create.php" class="btn btn-primary mb-3">Add Payment</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($payments as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['payment_id']) ?></td>
                <td><?= htmlspecialchars($row['order_id']) ?></td>
                <td>Rs. <?= htmlspecialchars($row['amount']) ?></td>
                <td><?= htmlspecialchars($row['method']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['payment_date']) ?></td>
                <td>
                    <a href="edit.php?id=<?= htmlspecialchars($row['payment_id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete.php?id=<?= htmlspecialchars($row['payment_id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this payment?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once '../../includes/footer.php'; ?>
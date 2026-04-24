<?php
session_start();
require_once '../config/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

if (!isset($_GET['order_id'])) {
    echo "<div class='container mt-4'><div class='alert alert-danger text-center'>Invalid order.</div></div>";
    require_once '../includes/footer.php';
    exit();
}

$order_id = intval($_GET['order_id']);

try {
    // Fetch order info (payment_status is in WebsiteOrders, payment method/status in Payments)
    $order_sql = "
        SELECT o.*, pay.method AS payment_method, pay.status AS payment_status, s.carrier, s.tracking_number, s.status AS shipment_status
        FROM WebsiteOrders o
        LEFT JOIN Payments pay ON o.order_id = pay.order_id
        LEFT JOIN CustomerShipments s ON o.order_id = s.order_id
        WHERE o.order_id = ? AND o.customer_id = ?
        LIMIT 1
    ";
    $stmt = $pdo->prepare($order_sql);
    $stmt->execute([$order_id, $customer_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo "<div class='container mt-4'><div class='alert alert-danger text-center'>Order not found.</div></div>";
        require_once '../includes/footer.php';
        exit();
    }

    // Fetch ordered items (OrderItems table, product_id = asin)
    $items_sql = "
        SELECT p.name AS product_name, p.retail_price AS price, oi.quantity
        FROM OrderItems oi
        JOIN Products p ON oi.product_id = p.asin
        WHERE oi.order_id = ?
    ";
    $item_stmt = $pdo->prepare($items_sql);
    $item_stmt->execute([$order_id]);
    $items = $item_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2 class="text-center mb-4">Order Details - #<?= htmlspecialchars($order_id) ?></h2>
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Order Date:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
            <p><strong>Total:</strong> PKR <?= number_format($order['total_amount'], 2) ?></p>
            <p><strong>Payment Method:</strong> <?= htmlspecialchars($order['payment_method'] ?? 'N/A') ?></p>
            <p><strong>Payment Status:</strong> <?= htmlspecialchars($order['payment_status'] ?? $order['payment_status'] ?? 'Pending') ?></p>

            <?php if ($order['carrier']): ?>
                <p><strong>Shipment Carrier:</strong> <?= htmlspecialchars($order['carrier']) ?></p>
                <p><strong>Tracking Number:</strong> <?= htmlspecialchars($order['tracking_number']) ?></p>
                <p><strong>Shipment Status:</strong> <?= htmlspecialchars($order['shipment_status']) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <h3 class="mb-3">Ordered Items</h3>
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Product</th>
                <th>Price (PKR)</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $grand_total = 0;
            foreach ($items as $item):
                $subtotal = $item['price'] * $item['quantity'];
                $grand_total += $subtotal;
            ?>
            <tr>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td><?= number_format($item['price'], 2) ?></td>
                <td><?= htmlspecialchars($item['quantity']) ?></td>
                <td><?= number_format($subtotal, 2) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                <td><strong>PKR <?= number_format($grand_total, 2) ?></strong></td>
            </tr>
        </tbody>
    </table>
</div>

<?php require_once '../includes/footer.php'; ?>

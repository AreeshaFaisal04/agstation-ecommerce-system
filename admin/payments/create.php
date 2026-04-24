<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = trim($_POST['order_id']);
    $method = trim($_POST['method']);
    $amount = trim($_POST['amount']);
    $status = trim($_POST['status']);

    try {
        // Insert the payment into the database
        $query = "INSERT INTO Payments (order_id, method, amount, status) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$order_id, $method, $amount, $status]);
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        $error = "Error adding payment: " . $e->getMessage();
    }
}

try {
    // Fetch orders for the dropdown
    $order_query = "SELECT id FROM orders";
    $order_stmt = $pdo->prepare($order_query);
    $order_stmt->execute();
    $orders = $order_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container">
    <h2>Add Payment</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <label>Order ID</label>
        <select name="order_id" required>
            <?php foreach ($orders as $order): ?>
                <option value="<?= htmlspecialchars($order['id']) ?>">#<?= htmlspecialchars($order['id']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Method</label>
        <select name="method" required>
            <option value="Card">Card</option>
            <option value="PayPal">PayPal</option>

















<?php require_once '../../includes/footer.php'; ?></div>    </form>        <button type="submit" class="btn btn-primary">Create</button>        </select>            <option value="Refunded">Refunded</option>            <option value="Failed">Failed</option>            <option value="Paid">Paid</option>        <select name="status" required>        <label>Status</label>        <input type="number" step="0.01" name="amount" required>        <label>Amount</label>        </select>
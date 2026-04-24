<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = trim($_POST['order_id']);
    $product_id = trim($_POST['product_id']);
    $reason = trim($_POST['reason']);
    $resolution = trim($_POST['resolution']);
    $refund_amount = trim($_POST['refund_amount']);

    try {
        // Insert the return into the database
        $query = "INSERT INTO Returns (order_id, product_id, reason, resolution, refund_amount, return_date)
                  VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$order_id, $product_id, $reason, $resolution, $refund_amount]);
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<div class="container">
    <h2>Create Return</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Order ID</label>
            <input type="number" name="order_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Product ID</label>
            <input type="text" name="product_id" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Reason</label>
            <textarea name="reason" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Resolution</label>
            <select name="resolution" class="form-select">
                <option value="Refund">Refund</option>
                <option value="Replacement">Replacement</option>
                <option value="Store Credit">Store Credit</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Refund Amount</label>
            <input type="number" step="0.01" name="refund_amount" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>

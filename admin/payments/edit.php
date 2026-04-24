<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';

// Validate and sanitize the ID parameter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

try {
    // Fetch the payment details
    $query = "SELECT * FROM Payments WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$payment) {
        header("Location: index.php");
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $method = trim($_POST['method']);
        $amount = trim($_POST['amount']);
        $status = trim($_POST['status']);

        // Update the payment details
        $update_query = "UPDATE Payments SET method = ?, amount = ?, status = ? WHERE id = ?";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([$method, $amount, $status, $id]);
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container">
    <h2>Edit Payment</h2>
    <form method="POST">
        <label>Method</label>
        <select name="method" required>
            <option value="Card" <?= $payment['method'] == 'Card' ? 'selected' : '' ?>>Card</option>
            <option value="PayPal" <?= $payment['method'] == 'PayPal' ? 'selected' : '' ?>>PayPal</option>
        </select>

        <label>Amount</label>
        <input type="number" step="0.01" name="amount" value="<?= htmlspecialchars($payment['amount']) ?>" required>

        <label>Status</label>
        <select name="status" required>
            <option value="Paid" <?= $payment['status'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
            <option value="Failed" <?= $payment['status'] == 'Failed' ? 'selected' : '' ?>>Failed</option>
            <option value="Refunded" <?= $payment['status'] == 'Refunded' ? 'selected' : '' ?>>Refunded</option>
        </select>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>
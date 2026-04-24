<?php
// filepath: d:\agstation\admin\expenses\edit.php
include '../../includes/session.php';
require_once '../../config/db.php';

checkRole(['admin', 'Admin', 1]);
// Validate and sanitize the ID parameter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

try {
    // Fetch the expense details
    $query = "SELECT * FROM Expenses WHERE expense_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $expense = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$expense) {
        header("Location: index.php");
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $type = trim($_POST['type']);
        $amount = trim($_POST['amount']);
        $paid_to = trim($_POST['paid_to']);
        $date = trim($_POST['date']);
        $notes = trim($_POST['notes']);
        $invoice_id = trim($_POST['invoice_id']);
        $service_id = !empty($_POST['service_id']) ? trim($_POST['service_id']) : null;

        // Update the expense details
        $update = "UPDATE Expenses 
                   SET type = ?, amount = ?, paid_to = ?, date = ?, notes = ?, invoice_id = ?, service_id = ? 
                   WHERE expense_id = ?";
        $stmt = $pdo->prepare($update);
        $stmt->execute([$type, $amount, $paid_to, $date, $notes, $invoice_id, $service_id, $id]);
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}

include '../../includes/header.php';
include '../../includes/navbar.php';
include '../../includes/sidebar.php';
?>

<div class="container mt-4">
    <h2>Edit Expense</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <input type="text" name="type" class="form-control" value="<?= htmlspecialchars($expense['type'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" value="<?= htmlspecialchars($expense['amount'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="paid_to" class="form-label">Paid To</label>
            <input type="text" name="paid_to" class="form-control" value="<?= htmlspecialchars($expense['paid_to'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($expense['date'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" class="form-control"><?= htmlspecialchars($expense['notes'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
            <label for="invoice_id" class="form-label">Invoice ID</label>
            <input type="number" name="invoice_id" class="form-control" value="<?= htmlspecialchars($expense['invoice_id'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="service_id" class="form-label">Service ID (optional)</label>
            <input type="number" name="service_id" class="form-control" value="<?= htmlspecialchars($expense['service_id'] ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
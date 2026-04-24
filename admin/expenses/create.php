<?php
include '../../includes/session.php';
require_once '../../config/db.php';
checkRole(['admin', 'Admin', 1]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = trim($_POST['type']);
    $amount = trim($_POST['amount']);
    $paid_to = trim($_POST['paid_to']);
    $date = trim($_POST['date']);
    $notes = trim($_POST['notes']);
    $invoice_id = trim($_POST['invoice_id']);
    $service_id = !empty($_POST['service_id']) ? trim($_POST['service_id']) : null;

    try {
        // Insert the expense into the database
        $query = "INSERT INTO Expenses (type, amount, paid_to, date, notes, invoice_id, service_id)
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$type, $amount, $paid_to, $date, $notes, $invoice_id, $service_id]);
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        $error = "Error adding expense: " . $e->getMessage();
    }
}
?>

<div class="container mt-4">
    <h2>Add Expense</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <input type="text" name="type" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="paid_to" class="form-label">Paid To</label>
            <input type="text" name="paid_to" class="form-control">
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="invoice_id" class="form-label">Invoice ID</label>
            <input type="number" name="invoice_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="service_id" class="form-label">Service ID (optional)</label>
            <input type="number" name="service_id" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>
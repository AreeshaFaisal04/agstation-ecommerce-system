<?php
include '../../../includes/session.php';
include '../../../includes/header.php';
require_once '../../../config/db.php';

checkRole(['Admin', 'admin', 1]); // Ensure the user has admin privileges (case-insensitive)
// Validate and sanitize the ID parameter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php");
    exit;
}

try {
    // Fetch the invoice details
    $query = "SELECT * FROM CustomerInvoices WHERE invoice_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    $invoice = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$invoice) {
        header("Location: index.php");
        exit;
    }

    // Fetch customers for the dropdown
    $customer_query = "SELECT customer_id, name FROM WebsiteCustomers";
    $customer_stmt = $pdo->prepare($customer_query);
    $customer_stmt->execute();
    $customers = $customer_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $customer_id = trim($_POST['customer_id']);
        $issue_date = trim($_POST['issue_date']);
        $due_date = trim($_POST['due_date']);
        $total_amount = trim($_POST['total_amount']);
        $status = trim($_POST['status']);
        $items = trim($_POST['items']);

        // Update the customer invoice
        $update_query = "UPDATE CustomerInvoices 
                         SET customer_id = ?, issue_date = ?, due_date = ?, total_amount = ?, status = ?, items = ? 
                         WHERE invoice_id = ?";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->execute([$customer_id, $issue_date, $due_date, $total_amount, $status, $items, $id]);
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
?>

<div class="container mt-4">
    <h2>Edit Customer Invoice</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="customer_id" class="form-label">Customer</label>
            <select name="customer_id" class="form-select" required>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= htmlspecialchars($customer['customer_id']) ?>" <?= ($customer['customer_id'] == $invoice['customer_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($customer['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="issue_date" class="form-label">Issue Date</label>
            <input type="date" name="issue_date" class="form-control" value="<?= htmlspecialchars($invoice['issue_date']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control" value="<?= htmlspecialchars($invoice['due_date']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="total_amount" class="form-label">Total Amount</label>
            <input type="number" step="0.01" name="total_amount" class="form-control" value="<?= htmlspecialchars($invoice['total_amount']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <?php foreach (['Unpaid', 'Paid', 'Overdue'] as $s): ?>
                    <option value="<?= htmlspecialchars($s) ?>" <?= ($s == $invoice['status']) ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="items" class="form-label">Items</label>
            <textarea name="items" class="form-control" rows="4"><?= htmlspecialchars($invoice['items']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<?php include '../../../includes/footer.php'; ?>

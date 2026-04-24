<?php
// filepath: d:\agstation\admin\invoices\customer\create.php
include '../../../includes/session.php';
require_once '../../../config/db.php';
checkRole(['Admin', 'admin', 1]); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = trim($_POST['customer_id']);
    $issue_date = trim($_POST['issue_date']);
    $due_date = trim($_POST['due_date']);
    $total_amount = trim($_POST['total_amount']);
    $status = trim($_POST['status']);
    $items = trim($_POST['items']);

    try {
        // Insert the customer invoice into the database
        $query = "INSERT INTO CustomerInvoices (customer_id, issue_date, due_date, total_amount, status, items)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$customer_id, $issue_date, $due_date, $total_amount, $status, $items]);
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        $error = "Error adding customer invoice: " . $e->getMessage();
    }
}

try {
    // Fetch customers for the dropdown
    $customer_query = "SELECT customer_id, name FROM WebsiteCustomers";
    $customer_stmt = $pdo->prepare($customer_query);
    $customer_stmt->execute();
    $customers = $customer_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

include '../../../includes/header.php';
?>

<div class="container mt-4">
    <h2>Add Customer Invoice</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="customer_id" class="form-label">Customer</label>
            <select name="customer_id" class="form-select" required>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= htmlspecialchars($customer['customer_id']) ?>"><?= htmlspecialchars($customer['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="issue_date" class="form-label">Issue Date</label>
            <input type="date" name="issue_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="total_amount" class="form-label">Total Amount</label>
            <input type="number" step="0.01" name="total_amount" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="Unpaid">Unpaid</option>
                <option value="Paid">Paid</option>
                <option value="Overdue">Overdue</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="items" class="form-label">Items</label>
            <textarea name="items" class="form-control" rows="4"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

<?php include '../../../includes/footer.php'; ?>

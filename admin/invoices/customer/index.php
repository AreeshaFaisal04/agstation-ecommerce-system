<?php
include '../../../includes/session.php';
include '../../../includes/header.php';
require_once '../../../config/db.php';
checkRole(['Admin', 'admin', 1]); // Accepts role name or role id


try {
    // Fetch customer invoices with customer details
    $query = "SELECT ci.invoice_id, ci.issue_date, ci.due_date, ci.total_amount, ci.status, wc.name 
              FROM CustomerInvoices ci
              JOIN WebsiteCustomers wc ON ci.customer_id = wc.customer_id
              ORDER BY ci.invoice_id DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2>Customer Invoices</h2>
    <a href="create.php" class="btn btn-success mb-3">Add Invoice</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Issue Date</th>
                <th>Due Date</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoices as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['invoice_id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['issue_date']) ?></td>
                <td><?= htmlspecialchars($row['due_date']) ?></td>
                <td><?= htmlspecialchars($row['total_amount']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <a href="edit.php?id=<?= htmlspecialchars($row['invoice_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete.php?id=<?= htmlspecialchars($row['invoice_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete invoice?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../../../includes/footer.php'; ?>

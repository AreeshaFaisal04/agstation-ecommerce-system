<?php
include '../../../includes/session.php';
include '../../../includes/header.php';
require_once '../../../config/db.php';
checkRole(['Admin', 'admin', 1]); // Ensure the user has admin privileges (case-insensitive)    
try {
    // Fetch seller invoices with related details
    $query = "SELECT si.invoice_id, si.issue_date, si.due_date, si.total_amount, si.status, 
                     p.payment_id, b.name AS brand_name, d.name AS distributor_name, s.name AS service_name
              FROM SellerInvoices si
              LEFT JOIN Payments p ON si.payment_id = p.payment_id
              LEFT JOIN Brands b ON si.brand_id = b.brand_id
              LEFT JOIN Distributors d ON si.distributor_id = d.distributor_id
              LEFT JOIN ThirdPartyServices s ON si.service_id = s.service_id
              ORDER BY si.invoice_id DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2>Seller Invoices</h2>
    <a href="create.php" class="btn btn-success mb-3">Add Invoice</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Payment ID</th>
                <th>Brand</th>
                <th>Distributor</th>
                <th>Service</th>
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
                <td><?= htmlspecialchars($row['payment_id']) ?></td>
                <td><?= htmlspecialchars($row['brand_name'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($row['distributor_name'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($row['service_name'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($row['issue_date']) ?></td>
                <td><?= htmlspecialchars($row['due_date']) ?></td>
                <td><?= htmlspecialchars($row['total_amount']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                    <a href="edit.php?id=<?= htmlspecialchars($row['invoice_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete.php?id=<?= htmlspecialchars($row['invoice_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this invoice?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../../../includes/footer.php'; ?>
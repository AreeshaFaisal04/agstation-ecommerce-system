<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin']);

try {
    // Fetch customer shipments
    $query = "
        SELECT cs.*, wo.order_date, wc.name AS customer_name
        FROM CustomerShipments cs
        JOIN WebsiteOrders wo ON cs.order_id = wo.order_id
        JOIN WebsiteCustomers wc ON wo.customer_id = wc.customer_id
        ORDER BY cs.shipment_id DESC
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $shipments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2>Customer Shipments</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Carrier</th>
                <th>Tracking Number</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($shipments as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['shipment_id']) ?></td>
                <td><?= htmlspecialchars($row['order_id']) ?></td>
                <td><?= htmlspecialchars($row['customer_name']) ?></td>
                <td><?= htmlspecialchars($row['carrier']) ?></td>
                <td><?= htmlspecialchars($row['tracking_number']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['order_date']) ?></td>
                <td>
                    <a href="edit.php?id=<?= htmlspecialchars($row['shipment_id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="delete.php?id=<?= htmlspecialchars($row['shipment_id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="create.php" class="btn btn-success">Add New Shipment</a>
</div>

<?php include_once '../../includes/footer.php'; ?>
<?php
require_once '../../config/db.php';
require_once '../../includes/session.php';
require_once '../../includes/header.php';
checkRole(['admin']);

try {
    // Fetch shipments from suppliers
    $query = "
        SELECT sfs.*, d.name AS distributor_name, b.name AS brand_name, w.name AS warehouse_name
        FROM ShipmentsFromSuppliers sfs
        LEFT JOIN Distributors d ON sfs.distributor_id = d.distributor_id
        LEFT JOIN Brands b ON sfs.brand_id = b.brand_id
        LEFT JOIN Warehouse w ON sfs.warehouse_id = w.warehouse_id
        ORDER BY sfs.shipment_id DESC
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $shipments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2>Shipments from Suppliers</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Distributor</th>
                <th>Brand</th>
                <th>Warehouse</th>
                <th>Shipment Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($shipments as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['shipment_id']) ?></td>
                <td><?= htmlspecialchars($row['distributor_name']) ?></td>
                <td><?= htmlspecialchars($row['brand_name']) ?></td>
                <td><?= htmlspecialchars($row['warehouse_name']) ?></td>
                <td><?= htmlspecialchars($row['shipment_date']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
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

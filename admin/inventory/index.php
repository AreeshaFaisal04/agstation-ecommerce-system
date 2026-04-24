<?php
include '../../includes/session.php';
include '../../includes/header.php';
require_once '../../config/db.php';
checkRole(['Admin', 'admin', 1]); // Accepts role name or role id

try {
    // Fetch inventory data with product and warehouse details
    $query = "SELECT i.inventory_id, i.quantity, i.last_updated, 
                     p.name AS product_name, 
                     w.name AS warehouse_name
              FROM Inventory i
              JOIN Products p ON i.product_id = p.asin
              JOIN Warehouse w ON i.warehouse_id = w.warehouse_id
              ORDER BY i.inventory_id DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-4">
    <h2>Inventory Management</h2>
    <a href="create.php" class="btn btn-success mb-3">Add Inventory Entry</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Warehouse</th>
                <th>Quantity</th>
                <th>Last Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inventory as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['inventory_id']) ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= htmlspecialchars($row['warehouse_name']) ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td><?= htmlspecialchars($row['last_updated']) ?></td>
                <td>
                    <a href="edit.php?id=<?= htmlspecialchars($row['inventory_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete.php?id=<?= htmlspecialchars($row['inventory_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this entry?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>

